<?php

namespace App\Http\Controllers;

use App\Models\Job_history;
use App\Http\Requests\StoreJob_historyRequest;
use App\Http\Requests\UpdateJob_historyRequest;
use App\Http\Resources\JobHistoryCollection;
use App\Models\Employee;
use App\Models\employeeview as ModelsEmployeeview;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class JobHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function jobhistoryByEmployee($id)
    {

        try {

            $jobhistories = QueryBuilder::for(Job_history::class)
                ->where('employee_id', $id)
                ->get();

            $success['data'] = new JobHistoryCollection($jobhistories);
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
    public function store(Request $request)
    {
        try {

            // $validated = $request->validated();


            $lastjob = QueryBuilder::for(Job_history::class)
                ->where('employee_id', $request->employee_id)
                ->latest('created_at')->first();
            if ($lastjob) {
                $lastjob->end_date = $request->end_date;
                $lastjob->save();
            }


            $job_history = Job_history::create(
                [
                    'start_date' => $request->start_date,
                    'end_date' => null,
                    'employee_id' => $request->employee_id,
                    'department_id' => $request->department_id,
                    'job_id' => $request->job_id,
                    'basic_salary' => $request->basic_salary,
                    'total_salary' => $request->total_salary
                ]

            );


            $employee = Employee::find($request->employee_id);
            $employee->salary = $request->basic_salary;
            $employee->total_salary = $request->total_salary;
            $employee->job_current = $request->job_id;
            $employee->department_current = $request->department_id;

            $employee->save();

            $success['data'] = $job_history;
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
    public function show(Job_history $job_history)
    {
        $employee = Employee::find($job_history->employee_id);

        // $jobs = QueryBuilder::for(Job_history::class)
        //     ->where('employee_id', $job_history->employee_id)
        //     ->get();
        // $view = new employeeview($employee, $jobs);
        return response()->json(['data' =>  $employee, 200]);
    }

    public function getview(Request $job_history)
    {
        $employee = Employee::find($job_history->employee_id);

        $jobs = QueryBuilder::for(Job_history::class)
            ->where('employee_id', $job_history->employee_id)
            ->get();
        $view = new ModelsEmployeeview($employee, $jobs);
        return response()->json(['data' => $view, 200]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJob_historyRequest $request, Job_history $job_history)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteJob($id)
    {
        try {

            $job_history = Job_history::find($id);
            $job_history->delete();
            $employee_id =  $job_history->employee_id;
            $lastjob = QueryBuilder::for(Job_history::class)
                ->where('employee_id', $employee_id)
                ->latest('created_at')->first();

            if (!$lastjob) {
                $employee = Employee::find($employee_id);
                $employee->salary = 0;
                $employee->total_salary = 0;
                $employee->save();
            } else {
                $employee = Employee::find($employee_id);
                $employee->salary = $lastjob->basic_salary;
                $employee->total_salary = $lastjob->total_salary;
                $employee->job_current = $lastjob->job_id;
                $employee->department_current = $lastjob->department_id;
                $employee->save();
            }
            $success['data'] = 'record deleted successfully';
            $success['success'] = true;
            return response()->json($success, 200);
        } catch (\Throwable $th) {
            $success['error'] = $th->getMessage();
            $success['success'] = false;
            return response()->json($success, 500);
        }
    }
}
