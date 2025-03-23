<?php

namespace App\Http\Controllers;

use App\Models\Trash;
use App\Http\Requests\StoreTrashRequest;
use App\Http\Requests\UpdateTrashRequest;
use App\Http\Resources\TrashResource;
use App\Models\Achievement;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Pioneer;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Storage;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $trashes = QueryBuilder::for(Trash::class)
            ->orderBy('id', 'desc')
            ->allowedFilters(['type'])
            ->get();
        return response()->json(['success' => true, 'data' => $trashes], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function deleteobject(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'type' => 'required|max:255',
            'object_id' => 'required',

        ]);

        if ($validated->fails()) {
            return response()->json(['success' => false, 'message' => $validated->errors()], 400);
        }

        $type = $request->type;
        $object_id = $request->object_id;

        $objectintrash = Trash::where('type', $type)
            ->where('object_id', $object_id)->first();
        if ($objectintrash) {
            return response()->json(['success' => false, 'message' => 'object is already in trash', 'object' => $objectintrash], 400);
        }
        if ($type == 'pioneer') {

            $object = Pioneer::find($object_id);
            if (!$object) {
                return response()->json(['success' => true, 'message' => 'object not found'], 404);
            }
            $object->deleted = 1;
            $object->update();
            $trash = Trash::create([
                'type' => $type,
                'object_id' => $object_id
            ]);

            //delete relative data to trash
            foreach($object->galleries->where('deleted',0) as $item){
                $item->deleted=1;
                $item->update();
                $trash = Trash::create([
                    'type' => 'image gallery',
                    'object_id' => $item->id
                ]);
            }

            foreach($object->videos->where('deleted',0) as $item){
                $item->deleted=1;
                $item->update();
                $trash = Trash::create([
                    'type' => 'video',
                    'object_id' => $item->id
                ]);
            }

            foreach($object->achievements->where('deleted',0) as $item){
                $item->deleted=1;
                $item->update();
                $trash = Trash::create([
                    'type' => 'achievement',
                    'object_id' => $item->id
                ]);
            }

            return response()->json(['success' => true, 'data' => $trash], 200);
        }
        if ($type == 'image gallery') {
            $object = Gallery::find($object_id);
            if (!$object) {
                return response()->json(['success' => true, 'message' => 'object not found'], 404);
            }
            $object->deleted = 1;
            $object->update();
            $trash = Trash::create([
                'type' => $type,
                'object_id' => $object_id
            ]);
            return response()->json(['success' => true, 'data' => $trash], 200);
        }
        if ($type == 'video') {
            $object = Video::find($object_id);
            if (!$object) {
                return response()->json(['success' => true, 'message' => 'object not found'], 404);
            }
            $object->deleted = 1;
            $object->update();
            $trash = Trash::create([
                'type' => $type,
                'object_id' => $object_id
            ]);
            return response()->json(['success' => true, 'data' => $trash], 200);
        }
        if ($type == 'achievement') {
            $object = Achievement::find($object_id);
            if (!$object) {
                return response()->json(['success' => true, 'message' => 'object not found'], 404);
            }
            $object->deleted = 1;
            $object->update();
            $trash = Trash::create([
                'type' => $type,
                'object_id' => $object_id
            ]);
            return response()->json(['success' => true, 'data' => $trash], 200);
        }
        if ($type == 'news') {
            $object = News::find($object_id);
            if (!$object) {
                return response()->json(['success' => true, 'message' => 'object not found'], 404);
            }
            $object->deleted = 1;
            $object->update();
            $trash = Trash::create([
                'type' => $type,
                'object_id' => $object_id
            ]);
            return response()->json(['success' => true, 'data' => $trash], 200);
        }
    }


    public function deleteobjectfromdb($id){
        $deleted_object = Trash::find($id);
        if (!$deleted_object) {
            return response()->json(['success' => true, 'message' => 'object not found'], 404);
        }
        $object_id = $deleted_object->object_id;
        $type = $deleted_object->type;

        if ($type == 'pioneer') {

            $object = Pioneer::find($object_id);

            foreach($object->galleriesAll as $item){
                
                
                app('App\Http\Controllers\GalleryController')->destroy($item);
                $deleted_item = QueryBuilder::for(Trash::class)
                ->where('object_id',$item->id)
                ->where('type','image gallery')->first();
                $deleted_item->delete();
              
            }

            foreach($object->videosAll as $item){
                
                
                app('App\Http\Controllers\VideoController')->destroy($item);
                $deleted_item = QueryBuilder::for(Trash::class)
                ->where('object_id',$item->id)
                ->where('type','video')->first();
                $deleted_item->delete();
              
            }

            foreach($object->achievementsAll as $item){
                
                
                app('App\Http\Controllers\AchievementController')->destroy($item);
                $deleted_item = QueryBuilder::for(Trash::class)
                ->where('object_id',$item->id)
                ->where('type','achievement')->first();
                
                $deleted_item->delete();
              
            }
         
            app('App\Http\Controllers\PioneerController')->destroy($object);
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'Pioneer is deleted from Database'], 200);
        }
        if ($type == 'image gallery') {
            $object = Gallery::find($object_id);
            app('App\Http\Controllers\GalleryController')->destroy($object);
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'Gallery is deleted from Database'], 200);
        }
        if ($type == 'video') {
            $object = Video::find($object_id);
            app('App\Http\Controllers\VideoController')->destroy($object);
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'Video is deleted from Database'], 200);
        }
        if ($type == 'achievement') {
            $object = Achievement::find($object_id);
            app('App\Http\Controllers\AchievementController')->destroy($object);
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'Achievement is deleted from Database'], 200);
        }
        if ($type == 'news') {
            $object = News::find($object_id);
            app('App\Http\Controllers\NewsController')->destroy($object);
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'News is deleted from Database'], 200);
        }
    }

    public function restoreobject($id)
    {
        $deleted_object = Trash::find($id);
        if (!$deleted_object) {
            return response()->json(['success' => true, 'message' => 'object not found'], 404);
        }
        $object_id = $deleted_object->object_id;
        $type = $deleted_object->type;
        if ($type == 'pioneer') {

            $object = Pioneer::find($object_id);
            $object->deleted = 0; 
            $object->update();           
            $deleted_object->delete();


            $galleries = QueryBuilder::for(Gallery::class)
            ->where('pioneer_id',$object_id)
            ->get();
            foreach($galleries as $item){
                $item->deleted=0;
                $item->update();
                $deletedItem = QueryBuilder::for(Trash::class)
                ->where('object_id',$item->id)
                ->where('type','image gallery')->first();
                $Item = Trash::find($deletedItem->id);
                $Item->delete();
            }


            $videos = QueryBuilder::for(Video::class)
            ->where('pioneer_id',$object_id)
            ->get();
            foreach($videos as $item){
                $item->deleted=0;
                $item->update();
                $deletedItem = QueryBuilder::for(Trash::class)
                ->where('object_id',$item->id)
                ->where('type','video')->first();
                $Item = Trash::find($deletedItem->id);
                $Item->delete();
            }

            $achievements = QueryBuilder::for(Achievement::class)
            ->where('pioneer_id',$object_id)
            ->get();
            foreach($achievements as $item){
                $item->deleted=0;
                $item->update();
                $deletedItem = QueryBuilder::for(Trash::class)
                ->where('object_id',$item->id)
                ->where('type','achievement')->first();
                $Item = Trash::find($deletedItem->id);
                $Item->delete();
            }

            $count = count($achievements);
            return response()->json(['success' => true, 'message' => 'Pioneer is restored','achivemnt count'=>$count], 200);
        }
        if ($type == 'image gallery') {
            $object = Gallery::find($object_id);
            $object->deleted = 0;
            $object->update();
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'Image gallery is restored'], 200);
        }
        if ($type == 'video') {
            $object = Video::find($object_id);
            $object->deleted = 0;
            $object->update();
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'Video is restored'], 200);
        }
        if ($type == 'achievement') {
            $object = Achievement::find($object_id);
            $object->deleted = 0;
            $object->update();
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'achievement is restored'], 200);
        }
        if ($type == 'news') {
            $object = News::find($object_id);
            $object->deleted = 0;
            $object->update();
            $deleted_object->delete();
            return response()->json(['success' => true, 'message' => 'news is restored'], 200);
        }
    }


    public function restoreall()
    {

        $objects = QueryBuilder::for(Trash::class)->get();

        $objects_count = count($objects);
        if ($objects_count > 0) {
            foreach ($objects as $o) {
                if ($o->type == 'pioneer') {
                    $object = Pioneer::find($o->object_id);
                    $object->deleted = 0;
                    $object->update();
                    $o->delete();
                }

                if ($o->type == 'image gallery') {
                    $object = Gallery::find($o->object_id);
                    $object->deleted = 0;
                    $object->update();
                    $o->delete();
                }
                if ($o->type == 'video') {
                    $object = Video::find($o->object_id);
                    $object->deleted = 0;
                    $object->update();
                    $o->delete();
                }
                if ($o->type == 'achievement') {
                    $object = Achievement::find($o->object_id);
                    $object->deleted = 0;
                    $object->update();
                    $o->delete();
                }
                if ($o->type == 'news') {
                    $object = News::find($o->object_id);
                    $object->deleted = 0;
                    $object->update();
                    $o->delete();
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'all objects are restored',
                'Count of objects' => $objects_count
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'The Trash is empty',
                'Count of objects' => $objects_count
            ], 200);
        }
    }


    public function deleteall()
    {
        $objects = QueryBuilder::for(Trash::class)->get();

        $objects_count = count($objects);
        if ($objects_count > 0) {
            foreach ($objects as $o) {
                if ($o->type == 'pioneer') {
                    $object = Pioneer::find($o->object_id);
                    $object->delete();
                    $o->delete();
                    $storage = Storage::disk('public');


                    if (!$object->image_signture == null) {
                        if ($storage->exists($object->image_signture)) {
                            app('App\Http\Controllers\TrashController')->deleteimage($object->image_signture);
                        }
                    }
                    if (!$object->image_hero == null) {
                        if ($storage->exists($object->image_hero))
                            app('App\Http\Controllers\TrashController')->deleteimage($object->image_hero);
                    }
                    if (!$object->image_portrait == null) {
                        if ($storage->exists($object->image_portrait))
                            app('App\Http\Controllers\TrashController')->deleteimage($object->image_portrait);
                    }
                }

                if ($o->type == 'image gallery') {
                    $object = Gallery::find($o->object_id);
                    $object->delete();
                    $o->delete();
                    $storage = Storage::disk('public');

                    if (!$object->image_link == null) {
                        if ($storage->exists($object->image_link)) {
                            app('App\Http\Controllers\TrashController')->deleteimage($object->image_link);
                        }
                    }
                }
                if ($o->type == 'video') {
                    $object = Video::find($o->object_id);
                    $object->delete();
                    $o->delete();
                    $storage = Storage::disk('public');
                    if (!$object->video_link == null) {
                        if ($storage->exists($object->video_link)) {
                            app('App\Http\Controllers\TrashController')->deleteimage($object->video_link);
                        }
                    }
                }
                if ($o->type == 'achievement') {
                    $object = Achievement::find($o->object_id);
                    $object->delete();
                    $o->delete();
                    $storage = Storage::disk('public');
                    if (!$object->image == null) {
                        if ($storage->exists($object->image)) {
                            app('App\Http\Controllers\TrashController')->deleteimage($object->image);
                        }
                    }
                }
                if ($o->type == 'news') {
                    $object = News::find($o->object_id);
                    $object->delete();
                    $o->delete();
                    $storage = Storage::disk('public');
                    if (!$object->imageLink == null) {
                        if ($storage->exists($object->imageLink)) {
                            app('App\Http\Controllers\TrashController')->deleteimage($object->imageLink);
                        }
                    }
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'all objects are deleted',
                'Count of objects' => $objects_count
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'The Trash is empty',
                'Count of objects' => $objects_count
            ], 200);
        }
    }


    public function deleteimage($link)
    {
        $storage = Storage::disk('public');
        $image_path = $link;
        if ($storage->exists($image_path)) {
            $storage->delete($image_path);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Trash $trash)
    {
        return new TrashResource($trash);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trash $trash)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrashRequest $request, Trash $trash)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trash $trash)
    {
        //
    }
}
