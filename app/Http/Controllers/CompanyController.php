<?php

namespace App\Http\Controllers;

use App\Exports\CompaniesExport;
use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Psy\Readline\Hoa\Console;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $companies = QueryBuilder::for(Company::class)
                ->allowedFilters(['name'])
                ->get();
            $success['data'] = CompanyResource::collection($companies);
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
    public function store(StoreCompanyRequest $request)
    {
        try {
            $validated = $request->validated();
            $image_name = null;
            if ($request->hasFile('image')) {
                $image_name = Str::random(32) . "." . $request->image->getClientOriginalExtension();
                Storage::disk('public')->put($image_name, file_get_contents($request->image));
            }

            $company = Company::create([
                'companyName' => $validated['companyName'],
                'email' => $validated['email'],
                'phone1' => $validated['phone1'],
                'phone2' => $validated['phone2'],
                'fax' => $validated['fax'],
                'website' => $validated['website'],
                'about' => $validated['about'],
                'location' => $validated['location'],
                'address' => $validated['address'],
                'facebook' => $validated['facebook'],
                'twitter' => $validated['twitter'],
                'linkden' => $validated['linkden'],
                'skype' => $validated['skype'],
                'whatsapp' => $validated['whatsapp'],
                'instegram' => $validated['instegram'],
                'status' => $validated['status'],
                'license_number' => $validated['license_number'],
                'license_release' => $validated['license_release'],
                'license_expiry' => $validated['license_expiry'],
                'image' => $image_name
            ]);
            $success['data'] = $company;
            $success['message'] = 'Company added successfully';
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
    public function show(Company $company)
    {
        try {
            $success['data'] = new CompanyResource($company);
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    public function export() 
    {
        return Excel::download(new CompaniesExport, 'companies.xlsx');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        try {

            $validated = $request->validated();
            $image_name = null;
            if ($request->hasFile('image')) {
                $storage = Storage::disk('public');
                if ($storage->exists($company->image)) {
                    $image_path = $company->image;
                    if ($storage->exists($image_path)) {
                        $storage->delete($image_path);
                    }
                }
                $image_name = Str::random(32) . "." . $request->image->getClientOriginalExtension();
                Storage::disk('public')->put($image_name, file_get_contents($request->image));
                $validated['image'] = $image_name;
            }

            
            $company->update($validated);
            $success['data'] = new CompanyResource($company);
            $success['message'] = 'company updated successfully';
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
    public function destroy(Company $company)
    {
        try {



            $employee = QueryBuilder::for(Employee::class)
            ->where('company_id',$company->id)
            ->first(); 

            if(!$employee){
                $company->delete();
                $storage = Storage::disk('public');
                // if ($storage->exists($company->image)) {
                //     $image_path = $company->image;
                //     if ($storage->exists($image_path)) {
                //         $storage->delete($image_path);
                //     }
                // }
                $success['message'] = 'company Deleted successfully';
                $success['success'] = true;
                return response()->json($success, 200);
            }else{
                $success['message'] = 'This company has employees, you cant deleted';
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
