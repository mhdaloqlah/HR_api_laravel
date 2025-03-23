<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Imports\UsersSheetImport;
use App\Models\Job_history;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Enums\FilterOperator;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = QueryBuilder::for(Employee::class)
                ->allowedFilters(['job_current', 'nationality', AllowedFilter::scope('total_salary_between')])
                ->get();
            $success['data'] = EmployeeResource::collection($employees);
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    public function employeeattendance(Request $request)
    {
        try {
            $employees = QueryBuilder::for(Employee::class)
                ->allowedFilters(['job_current', 'nationality', AllowedFilter::scope('total_salary_between')])
                ->allowedFields([
                    'id',
                    'first_name',
                    'last_name',
                    'mid_name',
                    'father_name',
                    'mother_name',
                    'birth_date',
                    'birth_place',
                    'nationality',
                    'phone',
                    'mobile',
                    'email',
                    'gender',
                    'familty_status',
                    'child_number',
                    'address_current',
                    'address_permanent',
                    'image',
                    'department_hire',
                    'department_current',
                    'job_hire',
                    'job_current',
                    'salary',
                    'hire_date',
                    'end_date',
                    'status',
                    'company_id',
                    'address_incompany_id',
                    'total_salary',
                    'allowances',
                    'visa_expiry',
                    'visa_validity',
                    'finger',
                    'cancelation_date'
                ])
                ->get();
            $success['data'] = EmployeeResource::collection($employees);
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    public function getwithstatus($status)
    {
        try {
            $employees = QueryBuilder::for(Employee::class)
                ->allowedFilters(['job_current', 'nationality', AllowedFilter::scope('total_salary_between')])
                ->where('status', $status)
                ->get();
            $success['data'] = EmployeeResource::collection($employees);
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    public function updateStatus(Request $request)
    {
        try {

            $employee = Employee::find($request->id);
            // $employee->status = !$employee->status; 
            $status = $employee->status;
            $employee->update(['status' => !$status]);
            $success['data'] = new EmployeeResource($employee);
            $success['message'] = 'Employee Status Updated successfully';
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
        return Excel::download(new EmployeesExport, 'Employees.xlsx');
    }


    public function import()
    {
        Excel::import(new UsersSheetImport, 'data2.xlsx');

        return '1';
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {

            $validated = $request->validated();

            $employee_image = null;
            if ($request->image != null) {
                $employee_image = Str::random(32) . "." . $request->image->getClientOriginalExtension();
                Storage::disk('public')->put($employee_image, file_get_contents($request->image));
                $validated['image'] = $employee_image;
            }
            $employee = Employee::create($validated);


            //add these after emplyees document get 
            $job_history = Job_history::create([
                'start_date' => $validated['hire_date'],
                'end_date' => null,
                'job_id' => $validated['job_current'],
                'employee_id' => $employee->id,
                'department_id' => $validated['department_current'],
                'basic_salary' => $validated['salary'],
                'total_salary' => $validated['total_salary'],
            ]);


            $success['data'] = $employee;
            // $success['job_data'] = $job_history;
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
    public function show(Employee $employee)
    {
        try {
            $success['data'] = new EmployeeResource($employee);
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
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        try {
            $validated = $request->validated();
            $employee_image = $employee->image;
            if ($request->image != null) {
                // Public storage
                $storage = Storage::disk('public');
                // Old iamge delete
                if (!$employee->image == null) {
                    if ($storage->exists($employee->image))
                        $storage->delete($employee->image);
                }
                $employee_image = Str::random(32) . "." . $request->image->getClientOriginalExtension();
                Storage::disk('public')->put($employee_image, file_get_contents($request->image));
                $validated['image'] = $employee_image;
            }
            $employee->update($validated);
            $success['data'] = new EmployeeResource($employee);
            $success['message'] = 'Employee Updated successfully';
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
    public function destroy(Employee $employee)
    {
        try {
            $storage = Storage::disk('public');
            // Old iamge delete
            if (!$employee->image == null) {
                if ($storage->exists($employee->image))
                    $storage->delete($employee->image);
            }
            $employee->delete();
            $success['message'] = 'Employee Deleted successfully';
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }
}
