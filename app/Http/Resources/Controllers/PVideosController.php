<?php

namespace App\Http\Controllers;

use App\Models\PVideos;
use App\Http\Requests\StorePVideosRequest;
use App\Http\Requests\UpdatePVideosRequest;
use App\Http\Resources\PvideoCollection;
use App\Http\Resources\PvideoResource;
use Spatie\QueryBuilder\QueryBuilder;
class PVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = QueryBuilder::for(PVideos::class)
        ->get();
        return new PvideoCollection($videos);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePVideosRequest $request)
    {
        $validated = $request->validated();
        $video = PVideos::create($validated);
        return new PvideoResource($video);
    }

    /**
     * Display the specified resource.
     */
    public function show(PVideos $pVideos)
    {
        return new PvideoResource($pVideos);
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePVideosRequest $request, PVideos $pVideos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PVideos $pVideos)
    {
        $pVideos->delete();
        return response(
            [
                'message' => 'Video has been deleted from Profile'
            ]
        );
    }
}
