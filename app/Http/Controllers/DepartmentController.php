<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\DepartmentResource;
use App\Models\Employee;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $departments = QueryBuilder::for(Department::class)
                ->allowedFilters(['name'])
                ->get();
            $success['data'] = DepartmentResource::collection($departments);
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
    public function store(StoreDepartmentRequest $request)
    {

        try {
            $validated = $request->validated();
            $department = Department::create([
                'name' => $validated['name'],

            ]);
            $success['data'] = $department;
            $success['message'] = 'Department added successfully';
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
    public function show(Department $department)
    {
        try {
            $success['data'] = new DepartmentResource($department);
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
    public function update(UpdateDepartmentRequest $request, Department $department)
    {

        try {
            $department->update($request->validated());
            $success['data'] = new DepartmentResource($department);
            $success['message'] = 'Department updated successfully';
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
    public function destroy(Department $department)
    {

        try {

            $employee = QueryBuilder::for(Employee::class)
                ->where('department_hire', $department->id)
                ->orwhere('department_current', $department->id)
                ->first();

            if (!$employee) {
                $department->delete();
                $success['message'] = 'Department Deleted successfully';
                $success['success'] = true;
                return response()->json($success, 200);
            } else {
                $success['message'] = 'This Department has employees, you cant deleted';
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
