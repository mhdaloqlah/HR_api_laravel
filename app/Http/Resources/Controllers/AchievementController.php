<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Http\Requests\StoreAchievementRequest;
use App\Http\Requests\UpdateAchievementRequest;
use App\Http\Resources\AchievementCollection;
use App\Http\Resources\AchievementResource;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Achievements = QueryBuilder::for(Achievement::class)
            ->where('deleted', 0)
            ->get();
        // return new AchievementCollection($Achievements);
        return AchievementResource::collection($Achievements);
    }


    public function getAchievementByPioneerId(Request $request, int $id)
    {
        $Achievements = QueryBuilder::for(Achievement::class)
            ->where('pioneer_id', $id)
            ->where('deleted',0)
            ->orderBy('priority')
            ->get();
        return new AchievementCollection($Achievements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAchievementRequest $request)
    {
        $validated = $request->validated();
        $Achievement_path = $request->file('image')->store('images/pioneers/achievement', 'public');
        $Achievement = Achievement::create([
            'title' => $validated['title'],
            'title_ar' => $validated['title_ar'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'content_ar' => $validated['content_ar'],
            'pioneer_id' => $validated['pioneer_id'],
            'priority' => $validated['priority'],
            'image' => $Achievement_path,
        ]);

        return new AchievementResource($Achievement);
    }

    /**
     * Display the specified resource.
     */
    public function show(Achievement $achievement)
    {
        return new AchievementResource($achievement);
    }



    /**
     * Update the specified resource in storage.
     */
    public function updateAchievement(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'title' => 'sometimes|max:100|string',
            'title_ar' => 'sometimes|max:100|string',
            'content' => 'sometimes|max:3000|string',
            'content_ar' => 'sometimes|max:3000|string',
            'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'pioneer_id' => 'sometimes',
            'priority' => 'sometimes'


        ]);


        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => $validatedData->errors()], 400);
        }

        $achievement = Achievement::find($request->id);



        $achievement->title = $request->title;
        $achievement->title_ar = $request->title_ar;
        $achievement->content = $request->content;
        $achievement->content_ar = $request->content_ar;
        $achievement->priority = $request->priority;

        // $achievement->image = $request->image;
        // $achievement->pioneer_id = $request->pioneer_id;





        if ($request->image && $request->image->isValid()) {
            $storage = Storage::disk('public');
            if (!$achievement->image == null) {
                if ($storage->exists($achievement->image)) {
                    app('App\Http\Controllers\TrashController')->deleteimage($achievement->image);
                }
            }
            $image_path = $request->file('image')->store('images/pioneers/achievement', 'public');
            $achievement->image = $image_path;
        }



        $achievement->update();




        return response()->json([
            'success' => true, 'message' => 'achievement updated successfully!',
            'data' => new AchievementResource($achievement)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Achievement $achievement)
    {
        $achievement->delete();
        $storage = Storage::disk('public');
        if (!$achievement->image == null) {
            if ($storage->exists($achievement->image)) {
                app('App\Http\Controllers\TrashController')->deleteimage($achievement->image);
            }
        }
        return response(
            [
                'message' => 'Achievement has been deleted '
            ]
        );
    }
}
