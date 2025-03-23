<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = QueryBuilder::for(Category::class)
        ->allowedIncludes('Pioneers')
        ->get();
        
        // return new CategoryCollection($categories);
        return CategoryResource::collection($categories);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $category_path = $request->file('image')->store('images/category', 'public');

        $category = Category::create([
            'name'=> $validated['name'],
            'name_ar'=> $validated['name_ar'],
           
            'image'=> $category_path,
        ]);
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
      

        return new CategoryResource($category);
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function updateCategory(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'sometimes|max:100|string',
            'name_ar' => 'sometimes|max:100|string',
           
            'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            
            

        ]);

        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => $validatedData->errors()], 400);
        }
        $category= Category::find($request->id);
        $category->name = $request->name;
        $category->name_ar = $request->name_ar;
       
        if ($request->image && $request->image->isValid()) {

            $image_path = $request->file('image')->store('images/category', 'public');
            $category->image = $image_path;
        }

        $category->update();
        
        // return new CategoryResource($category);
        return response()->json([
            'success' => true, 'message' => 'Category updated successfully!',
            'data' => new CategoryResource($category)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response(
            [
                'message' => 'Category has been deleted',
                'message_ar'=>'تم حذف الفئة بنجاح'
            ]
        );
    }
}
