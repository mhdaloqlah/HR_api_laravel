<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Http\Resources\MediaCollection;
use App\Http\Resources\MediaResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medias = QueryBuilder::for(Media::class)
        ->get();
        return new MediaCollection($medias);
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaRequest $request)
    {
        $validated = $request->validated();
        $media_path = $request->file('media')->store('images');
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
       return new MediaResource($media);
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaRequest $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
       $media->delete();
       return response(
        [
            'message' => 'Media has been deleted'
        ]
    );
    }
}
