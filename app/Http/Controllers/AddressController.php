<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Employee;
use Spatie\QueryBuilder\QueryBuilder;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $addresses = QueryBuilder::for(Address::class)
                ->get();
            $success['data'] = AddressResource::collection($addresses);
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        try {
            $validated = $request->validated();
            $address = Address::create([
                'address_name' => $validated['address_name'],

            ]);
            $success['data'] = $address;
            $success['message'] = 'Address added successfully';
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        try {
            $success['data'] = new AddressResource($address);
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        try {
            $address->update($request->validated());
            $success['data'] = new AddressResource($address);
            $success['message'] = 'Address updated successfully';
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        try {
            $employee = QueryBuilder::for(Employee::class)
                ->where('address_incompany_id', $address->id)
                ->first();

            if (!$employee) {
                $address->delete();
                $success['message'] = 'Address Deleted successfully';
                $success['success'] = true;
                return response()->json($success, 200);
            } else {
                $success['message'] = 'This Address has employees, you cant deleted';
                $success['success'] = false;
                return response()->json($success, 200);
            }
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }
}
