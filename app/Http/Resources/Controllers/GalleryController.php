<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryCollection;
use App\Http\Resources\GalleryResource;
use App\Models\Pioneer;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = QueryBuilder::for(Gallery::class)
        ->allowedSorts(['priority'])
        ->where('deleted',0)
        ->orderBy('priority')
        ->get();
        return new GalleryCollection($images);
    }

    public function getPublicimages()
    {
        $images = QueryBuilder::for(Gallery::class)
        ->allowedSorts(['priority'])
        ->where('deleted',0)
        ->where('pioneer_id',0)
        ->orderBy('priority')
        ->get();
        return new GalleryCollection($images);
    }


    public function getGalleryByPioneerId(Request $request,int $id)
    {

        if($id>0){
            $images = QueryBuilder::for(Gallery::class)
            ->where('pioneer_id', $id)
            ->where('deleted',0)
            ->orderBy('priority')
            ->get();
            return new GalleryCollection($images);
            // return response()->json([
            //     'data' => new GalleryCollection($images),
            //     'pioneerName'=>$images->pioneer->first_name .' ' . $images->Pioneer->last_name
            // ]);
        }
        if($id==0){
            $images = QueryBuilder::for(Gallery::class)
        ->allowedSorts(['priority'])
        ->where('deleted',0)
        ->where('pioneer_id',0)
        ->orderBy('priority')
        ->get();
        return new GalleryCollection($images);
        }
        
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalleryRequest $request)
    {
        $validated = $request->validated();
        $pimage_path = $request->file('image_link')->store('images/pioneers/pimages', 'public');
        $pimage = Gallery::create([
            'priority'=> $validated['priority'],
            'image_link'=>$pimage_path,
            'pioneer_id'=> $validated['pioneer_id']
        ]);

        return new GalleryResource($pimage);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return new GalleryResource($gallery);
    }

  
    /**
     * Update the specified resource in storage.
     */
    public function updateimage(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'priority' => 'sometimes',
            'image_link' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'pioneer_id' => 'sometimes',
            

        ]);


        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => $validatedData->errors()], 400);
        }

        $pimage = Gallery::find($request->id);



        $pimage->priority = $request->priority;
        
       
     
        // $pimage->image_link = $request->image_link;
        // $pimage->pioneer_id = $request->pioneer_id;
       




        if ($request->image_link && $request->image_link->isValid()) {
            
            $storage = Storage::disk('public');
            if ($storage->exists($pimage->image_link)) {
                app('App\Http\Controllers\TrashController')->deleteimage($pimage->image_link);
            }

            $image_path = $request->file('image_link')->store('images/pioneers/pimages', 'public');
            $pimage->image_link = $image_path;
        }

       

        $pimage->update();




        return response()->json([
            'success' => true, 'message' => 'Image data updated successfully!',
            'data' => $pimage
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        $storage = Storage::disk('public');

        if (!$gallery->image_link == null) {
            if ($storage->exists($gallery->image_link)) {
                app('App\Http\Controllers\TrashController')->deleteimage($gallery->image_link);
            }
        }
        return response(
            [
                'message' => 'Image has been deleted from Gallery'
            ]
        );
    }
}
