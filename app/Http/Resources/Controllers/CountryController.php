<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryCollection;
use App\Http\Resources\CountryResource;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = QueryBuilder::for(Country::class)
        ->allowedIncludes('Pioneers')
        ->get();
        // return new CountryCollection($categories);
        return CountryResource::collection($countries);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request)
    {
        $validated = $request->validated();
        $country_path = $request->file('image')->store('images/country', 'public');

        $Country = Country::create([
            'name'=> $validated['name'],
            'name_ar'=> $validated['name_ar'],
           
            'image'=> $country_path,
        ]);
        return new CountryResource($Country);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $Country)
    {
        return new CountryResource($Country);
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function updateCountry(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'sometimes|max:100|string',
            'name_ar' => 'sometimes|max:100|string',
           
            'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            
            

        ]);

        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => $validatedData->errors()], 400);
        }
        $country= Country::find($request->id);
        $country->name = $request->name;
        $country->name_ar = $request->name_ar;
       
        if ($request->image && $request->image->isValid()) {

            $image_path = $request->file('image')->store('images/country', 'public');
            $country->image = $image_path;
        }

        $country->update();
        
        // return new CategoryResource($country);
        return response()->json([
            'success' => true, 'message' => 'Country updated successfully!',
            'data' => new CountryResource($country)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $Country)
    {
        $Country->delete();
        return response(
            [
                'message' => 'Country has been deleted',
                'message_ar'=> 'تم حذف البلد بنجاح'
            ]
        );
    }
}
