<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsResource;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news  = QueryBuilder::for(News::class)
        ->where('deleted',0)
        ->orderby('created_at','DESC')
        ->get();


        return new NewsCollection($news);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
       $validated = $request->validated();
       $imagenews = $request->file('imageLink')->store('images/news','public');
       $news = News::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'content' => $validated['content'],
            'brief' => $validated['brief'],
            'exclusive'=> $validated['exclusive'],
            'showauthor'=>$validated['showauthor'],
            'imageLink' => $imagenews,


       ]);

       return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return new NewsResource($news);
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function updateNews(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'title'=>'sometimes|max:100',
            'author'=>'sometimes|max:100',
            'content'=>'sometimes',
            'brief'=>'sometimes|max:3000',
            'imageLink'=>'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'exclusive'=>'sometimes',
            'showauthor'=>'sometimes'
                  
        ]);

        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => $validatedData->errors()], 400);
        }

        $news = News::find($request->id);

        $news->title = $request->title;
        $news->author = $request->author;
        $news->content = $request->content;
        $news->brief = $request->brief;
        $news->status = $request->status;
        $news->showauthor = $request->showauthor;
        $news->exclusive = $request->exclusive;
        if($request->imageLink && $request->imageLink->isValid()){
            $storage = Storage::disk('public');
            if (!$news->imageLink == null) {
                if ($storage->exists($news->imageLink)) {
                    app('App\Http\Controllers\TrashController')->deleteimage($news->imageLink);
                }
            }
            $imageLink = $request->file('imageLink')->store('images/News', 'public');
            $news->imageLink= $imageLink;


        }

        $news->update();

        return response()->json(['success' => true, 'message' => 'News updated successfully!', 
        'data' => $news], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();
        $storage = Storage::disk('public');
        if (!$news->imageLink == null) {
            if ($storage->exists($news->imageLink)) {
                app('App\Http\Controllers\TrashController')->deleteimage($news->imageLink);
            }
        }
        return response(
            [
                'message' => 'News record has been deleted'
            ]
        );
    }
}
