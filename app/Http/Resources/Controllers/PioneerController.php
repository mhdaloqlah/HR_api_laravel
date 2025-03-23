<?php

namespace App\Http\Controllers;

use App\Models\Pioneer;
use App\Http\Requests\StorePioneerRequest;
use App\Http\Requests\UpdatePioneerRequest;
use App\Http\Resources\PioneerCollection;
use App\Http\Resources\PioneerResource;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PioneerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pioneers = QueryBuilder::for(Pioneer::class)
            ->allowedFilters(['category_id', 'country_id', 'first_name', 'first_name_ar', 'excelents', 'uaetoworld'])
            ->where('deleted', 0)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Pioneers Data',
            'data' => PioneerResource::collection($pioneers)
        ], 200);
    }



    public function search($searchinput)
    {

        $pioneers = QueryBuilder::for(Pioneer::class)
            ->allowedFilters(['category_id', 'country_id', 'first_name', 'first_name_ar'])
            ->where('deleted', 0)
            ->whereRaw('CONCAT(first_name," ",last_name) LIKE \'%' . $searchinput . '%\' or CONCAT(first_name_ar," ",last_name_ar) LIKE \'%' . $searchinput . '%\'')
            ->get();
        $count = count($pioneers);
        if ($count > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Pioneers Data',
                'data' => PioneerResource::collection($pioneers),
                'pioneer found Count' => $count
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pioneers Data',
                'data' => PioneerResource::collection($pioneers),
                'pioneer found Count' => $count
            ], 200);
        }
    }




    public function pioneerslist()
    {


        $pioneers = QueryBuilder::for(Pioneer::class)

            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Pioneers Data',
            'data' => PioneerResource::collection($pioneers)
        ], 200);
    }

    public function pioneerslistparam($country_id = null, $category_id = null)
    {

        if ($country_id != null && $category_id != null) {
            $pioneers = QueryBuilder::for(Pioneer::class)
                ->where('country_id', $country_id)
                ->where('category_id', $category_id)
                ->get();
        }

        return new PioneerCollection($pioneers);
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'first_name' => 'sometimes|max:100',
            'last_name' => 'sometimes|max:100',
            'gender' => 'sometimes|max:50',
            'brief' => 'sometimes',
            'mission' => 'sometimes',
            'vision' => 'sometimes',
            'bio' => 'sometimes',
            'first_name_ar' => 'sometimes|max:100',
            'last_name_ar' => 'sometimes|max:100',
            'gender_ar' => 'sometimes|max:50',
            'brief_ar' => 'sometimes',
            'mission_ar' => 'sometimes',
            'vision_ar' => 'sometimes',
            'bio_ar' => 'sometimes',
            'bio_status' => 'sometimes',
            'brief_status' => 'sometimes',
            'mission_status' => 'sometimes',
            'vision_status' => 'sometimes',
            'facebook' => 'sometimes|max:200',
            'linkedin' => 'sometimes|max:200',
            'instagram' => 'sometimes|max:200',
            'youtube' => 'sometimes|max:200',
            'twitter' => 'sometimes|max:200',
            'email' => 'sometimes|max:200',
            'image_portrait' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'image_hero' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'image_signture' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'category_id' => 'sometimes',
            'country_id' => 'sometimes',
            'excelents' => 'sometimes',
            'uaetoworld' => 'sometimes'

        ]);



        if ($validated->fails()) {
            return response()->json(['success' => false, 'message' => $validated->errors()], 400);
        }


        $image_hero_pathName = Str::random(32) . "." . $request->image_hero->getClientOriginalExtension();
        $image_portraitName = Str::random(32) . "." . $request->image_portrait->getClientOriginalExtension();

        // Save Image in Storage folder
        Storage::disk('public')->put($image_hero_pathName, file_get_contents($request->image_hero));
        Storage::disk('public')->put($image_portraitName, file_get_contents($request->image_portrait));

        // $image_hero_path = $request->file('image_hero')->store('images/pioneers/hero', 'public');
        // $image_portrait_path = $request->file('image_portrait')->store('images/pioneers/portrait', 'public');
        $image_signture_pathName = null;
        if ($request->image_signture != null) {
            $image_signture_pathName = Str::random(32) . "." . $request->image_signture->getClientOriginalExtension();
            Storage::disk('public')->put($image_signture_pathName, file_get_contents($request->image_signture));

            // $image_signture_path = $request->file('image_signture')->store('images/pioneers/signture', 'public');
        }


        $pioneer = Pioneer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'brief' => $request->brief,
            'mission' => $request->mission,
            'vision' => $request->vision,
            'bio' => $request->bio,
            'bio_ar' => $request->bio_ar,
            'first_name_ar' => $request->first_name_ar,
            'last_name_ar' => $request->last_name_ar,
            'gender_ar' => $request->gender_ar,
            'brief_ar' => $request->brief_ar,
            'mission_ar' => $request->mission_ar,
            'vision_ar' => $request->vision_ar,
            'facebook' => $request->facebook,
            'bio_status' => $request->bio_status,
            'brief_status' => $request->brief_status,
            'mission_status' => $request->mission_status,
            'vision_status' => $request->vision_status,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'youtube' => $request->youtube,
            'twitter' => $request->twitter,
            'email' => $request->email,
            'image_portrait' => $image_portraitName,
            'image_hero' => $image_hero_pathName,
            'image_signture' => $image_signture_pathName,
            'category_id' => $request->category_id,
            'country_id' => $request->country_id,
            'excelents' => $request->excelents,
            'uaetoworld' => $request->uaetoworld
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pioneer Added successfully!',
            'data' => new PioneerResource($pioneer)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pioneer $pioneer)
    {
        return response()->json([
            'success' => true,
            'message' => 'Pioneer Data',
            'data' => new PioneerResource($pioneer)
        ], 200);
    }


    public function DeletePioneer($id)
    {
        $pioneer = Pioneer::find($id);
        $pioneer->deleted = 1;
        $pioneer->update();
       



        
        return response()->json([
            'success' => true,
            'message' => 'Pioneer Data is in Trash',
            'data' => new PioneerResource($pioneer)
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function updatePioneer(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'first_name' => 'sometimes|max:100',
            'last_name' => 'sometimes|max:100',
            'gender' => 'sometimes|max:50',
            'brief' => 'sometimes',
            'mission' => 'sometimes',
            'vision' => 'sometimes',
            'bio' => 'sometimes',
            'first_name_ar' => 'sometimes|max:100',
            'last_name_ar' => 'sometimes|max:100',
            'gender_ar' => 'sometimes|max:50',
            'brief_ar' => 'sometimes',
            'mission_ar' => 'sometimes',
            'vision_ar' => 'sometimes',
            'bio_ar' => 'sometimes',
            'facebook' => 'sometimes|max:200',
            'linkedin' => 'sometimes|max:200',
            'instagram' => 'sometimes|max:200',
            'youtube' => 'sometimes|max:200',
            'twitter' => 'sometimes|max:200',
            'email' => 'sometimes|max:200',
            'image_portrait' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'image_hero' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'image_signture' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'category_id' => 'sometimes',
            'country_id' => 'sometimes',
            'excelents' => 'sometimes',
            'uaetoworld' => 'sometimes'

        ]);


        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => $validatedData->errors()], 400);
        }

        $pioneer = Pioneer::find($request->id);


        if ($request->first_name != '' || $request->first_name != null) {
            $pioneer->first_name = $request->first_name;
        }
        if ($request->last_name != '' || $request->last_name != null) {
            $pioneer->last_name = $request->last_name;
        }

        if ($request->gender != '' || $request->gender != null) {
            $pioneer->gender = $request->gender;
        }

        $pioneer->brief = $request->brief;


        $pioneer->mission = $request->mission;


        $pioneer->vision = $request->vision;

        if ($request->bio != '' || $request->bio != null) {
            $pioneer->bio = $request->bio;
        }


        if ($request->first_name_ar == '' || $request->first_name_ar != null) {
            $pioneer->first_name_ar = $request->first_name_ar;
        }
        if ($request->last_name_ar != '' || $request->last_name_ar != null) {
            $pioneer->last_name_ar = $request->last_name_ar;
        }
        if ($request->gender_ar != '' || $request->gender_ar != null) {
            $pioneer->gender_ar = $request->gender_ar;
        }

        $pioneer->brief_ar = $request->brief_ar;


        $pioneer->mission_ar = $request->mission_ar;


        $pioneer->vision_ar = $request->vision_ar;

        if ($request->bio_ar != '' || $request->bio_ar != null) {
            $pioneer->bio_ar = $request->bio_ar;
        }


        $pioneer->category_id = $request->category_id;
        $pioneer->country_id = $request->country_id;

        $pioneer->bio_status = $request->bio_status;
        $pioneer->brief_status = $request->brief_status;
        $pioneer->mission_status = $request->mission_status;
        $pioneer->vision_status = $request->vision_status;

        $pioneer->facebook = $request->facebook;
        $pioneer->instagram = $request->instagram;
        $pioneer->linkedin = $request->linkedin;
        $pioneer->youtube = $request->youtube;
        $pioneer->twitter = $request->twitter;
        $pioneer->email = $request->email;
        $pioneer->excelents = $request->excelents;
        $pioneer->uaetoworld = $request->uaetoworld;
        if ($request->image_portrait && $request->image_portrait->isValid()) {

            // Public storage
            $storage = Storage::disk('public');
            // Old iamge delete
            if (!$pioneer->image_portrait == null) {
                if ($storage->exists($pioneer->image_portrait))
                    $storage->delete($pioneer->image_portrait);
            }
            // Image name
            $imageName = Str::random(32) . "." . $request->image_portrait->getClientOriginalExtension();
            $pioneer->image_portrait = $imageName;

            // Image save in public folder
            $storage->put($imageName, file_get_contents($request->image_portrait));
        }

        if ($request->image_hero && $request->image_hero->isValid()) {

            // $image_hero_path = $request->file('image_hero')->store('images/pioneers/hero', 'public');
            // $pioneer->image_hero = $image_hero_path;

            // Public storage
            $storage = Storage::disk('public');
            // Old iamge delete
            if (!$pioneer->image_hero == null) {
                if ($storage->exists($pioneer->image_hero))
                    $storage->delete($pioneer->image_hero);
            }
            // Image name
            $imageName = Str::random(32) . "." . $request->image_hero->getClientOriginalExtension();
            $pioneer->image_hero = $imageName;

            // Image save in public folder
            $storage->put($imageName, file_get_contents($request->image_hero));
        }


        if ($request->image_signture && $request->image_signture->isValid()) {

            // $image_signture_path = $request->file('image_signture')->store('images/pioneers/signture', 'public');
            // $pioneer->image_signture = $image_signture_path;
            // Public storage
            $storage = Storage::disk('public');
            // Old iamge delete
            if (!$pioneer->image_signture == null) {
                if ($storage->exists($pioneer->image_signture))
                    $storage->delete($pioneer->image_signture);
            }
            // Image name
            $imageName = Str::random(32) . "." . $request->image_signture->getClientOriginalExtension();
            $pioneer->image_signture = $imageName;

            // Image save in public folder
            $storage->put($imageName, file_get_contents($request->image_signture));
        }
        $pioneer->update();




        return response()->json([
            'success' => true,
            'message' => 'Pioneer updated successfully!',
            'data' => new PioneerResource($pioneer)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */


    public function deleteimage($link)
    {
        $storage = Storage::disk('public');
        $image_path = $link;
        if ($storage->exists($image_path)) {
            $storage->delete($image_path);
        }
    }
    public function destroy(Pioneer $pioneer)
    {
        $pioneer->delete();



        $storage = Storage::disk('public');


        if (!$pioneer->image_signture == null) {
            if ($storage->exists($pioneer->image_signture)) {
                app('App\Http\Controllers\TrashController')->deleteimage($pioneer->image_signture);
            }
        }
        if (!$pioneer->image_hero == null) {
            if ($storage->exists($pioneer->image_hero))
                app('App\Http\Controllers\TrashController')->deleteimage($pioneer->image_hero);
        }
        if (!$pioneer->image_portrait == null) {
            if ($storage->exists($pioneer->image_portrait))
                app('App\Http\Controllers\TrashController')->deleteimage($pioneer->image_portrait);
        }



        return response(
            [
                'message' => 'Pioneer record has been deleted',
                'message_ar' => 'تم حذف الشخصية بنجاح'
            ]
        );
    }
}
