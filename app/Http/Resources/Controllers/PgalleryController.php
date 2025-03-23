<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePgalleryRequest;
use App\Models\pgallery;

use App\Http\Resources\PgalleryCollection;
use App\Http\Resources\PgalleryResource;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class PgalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = QueryBuilder::for(Pgallery::class)
        ->get();
        return new PgalleryCollection($images);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePgalleryRequest $request)
    {
        $validated = $request->validated();
        $pimage_path = $request->file('image_link')->store('images/pioneers/pimages', 'public');
        $pimage = Pgallery::create([
            'image_name'=> $validated['image_name'],
            'image_link'=>$pimage_path,
            'pioneer_id'=> $validated['pioneer_id']
        ]);

        return new PgalleryResource($pimage);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pgallery $pImage)
    {
        return new PgalleryResource($pImage);
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function updateimage(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'image_name' => 'sometimes|max:100|string',
            'image_link' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'pioneer_id' => 'sometimes',
            

        ]);


        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => $validatedData->errors()], 400);
        }

        $pimage = Pgallery::find($request->id);



        $pimage->image_name = $request->image_name;
        
       
     
        $pimage->image_link = $request->image_link;
        $pimage->pioneer_id = $request->pioneer_id;
       




        if ($request->image_link && $request->image_link->isValid()) {

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
    public function destroy(Pgallery $pImage)
    {
       $pImage->delete();
       return response(
        [
            'message' => 'Image has been deleted from Gallery'
        ]
    );
    }
}
