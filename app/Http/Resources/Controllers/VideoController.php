<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\VideoCollection;
use App\Http\Resources\VideoResource;
use GuzzleHttp\Psr7\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = QueryBuilder::for(Video::class)
        ->where('deleted',0)
        ->get();
        return new VideoCollection($videos);
    }

    public function getVideosByPioneerId(Request $request,int $id)
    {
        if($id>0){
            $videos = QueryBuilder::for(Video::class)
            ->where('pioneer_id', $id)
            ->where('deleted',0)
            ->orderBy('priority')
            ->get();
            return new VideoCollection($videos);
        }

        if($id==0){
            $videos = QueryBuilder::for(Video::class)
            ->where('pioneer_id',0)
            ->where('deleted',0)
            ->orderBy('priority')
            ->get();
            return new VideoCollection($videos);
        }
       
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoRequest $request)
    {

        // return response()->json([
        //     'success' => true, 'message' => 'Image data updated successfully!',
            
        // ], 200);
        $validated = $request->validated();
        $video_path = $request->file('video_link')->store('videos/pioneers/videos', 'public');
        $video = Video::create([
            'priority'=> $validated['priority'],
            'video_link'=>$video_path,
            'pioneer_id'=> $validated['pioneer_id']
        ]);

        return new VideoResource($video);
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return new VideoResource($video);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function updatevideo(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'priority'=>'sometimes',
            
            'video_link'=>'file|mimes:mp4,ogx,oga,ogv,ogg,webm,avi,wmv',
            'pioneer_id'=>'sometimes',
            

        ]);


        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => $validatedData->errors()], 400);
        }

        $pvideo = Video::find($request->id);



        $pvideo->priority = $request->priority;
        
       
     
        // $pvideo->video_link = $request->video_link;
        // $pvideo->pioneer_id = $request->pioneer_id;
       




        if ($request->video_link && $request->video_link->isValid()) {
            $storage = Storage::disk('public');
        if (!$pvideo->video_link == null) {
            if ($storage->exists($pvideo->video_link)) {
                app('App\Http\Controllers\TrashController')->deleteimage($pvideo->video_link);
            }
        }
            $video_path = $request->file('video_link')->store('videos/pioneers/videos', 'public');
            $pvideo->video_link = $video_path;
        }

       

        $pvideo->update();




        return response()->json([
            'success' => true, 'message' => 'Video file updated successfully!',
            'data' => $pvideo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();
        $storage = Storage::disk('public');
        if (!$video->video_link == null) {
            if ($storage->exists($video->video_link)) {
                app('App\Http\Controllers\TrashController')->deleteimage($video->video_link);
            }
        }
        return response(
            [
                'message' => 'Video file has been deleted from Storage
                '
            ]
        );
    }
}
