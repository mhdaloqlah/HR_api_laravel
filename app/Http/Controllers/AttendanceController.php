<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceCollection;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmplyeeattendanceColection;
use App\Http\Resources\EmplyeeattendanceCollection;
use App\Http\Resources\EmplyeeattendanceResource;
use App\Imports\UsersSheetImport;
use App\Imports\UsersSheetsiteImport;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        error_log('Some message here.');
        return response()->json('dhhjdhh', 200);
    }

    public function import(Request $request)
    {

        try {
            $file = $request->file('file');
            Excel::import(new UsersSheetImport, $file);
            $success['data'] = 'Excel file uploaded successfully';
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['data'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 200);
        }
    }

    public function import2(Request $request)
    {

        try {
            $file = $request->file('file');
            Excel::import(new UsersSheetsiteImport, $file);
            $success['data'] = 'Excel file from site uploaded successfully';
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['data'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 200);
        }
    }

    public function employeeattendance(Request $request)
    {
        try {
            $data = QueryBuilder::for(Attendance::class)
                ->where('employee_id', $request->employee_id)
                ->where('Date', '>=', $request->date_from)
                ->where('Date', '<=', $request->date_to)
                ->get();
            $success['data'] = new AttendanceCollection($data);
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    public function allemployeeattendance2(Request $request)
    {
        try {

            $employees = QueryBuilder::for(Employee::class)
                ->where('status', true)
                ->get();

            $data = new AttendanceCollection([]);
            foreach ($employees as $employee) {
                $emp = QueryBuilder::for(Attendance::class)
                    ->where('employee_id', $employee->id)
                    ->where('Date', '>=', $request->date_from)
                    ->where('Date', '<=', $request->date_to)
                    ->get();


                $data->push($emp);
            }


            $success['data'] = $data;
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    public function allemployeeattendance(Request $request)
    {
        try {

            $employees = QueryBuilder::for(Employee::class)
                ->where('status', true)
                ->get();


         

            $data = new Collection([]);
            foreach ($employees as $emp) {
                $data->push(new EmplyeeattendanceResource($emp, $request->date_from, $request->date_to));
            }
            
            $success['data'] =  $data;
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }

    public function updateAttendance(Request $request)
    {

        $attendance = Attendance::find($request->id);
        $attendance->CheckInTime = $request->CheckInTime;
        $attendance->CheckOutTime = $request->CheckOutTime;
        $attendance->Status = $request->Status;
        $attendance->save();
        $success['data'] = 'Data Saved Successfully';
        $success['success'] = true;
        return response()->json($success, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
