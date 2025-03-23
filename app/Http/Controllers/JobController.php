<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Employee;
use Spatie\QueryBuilder\QueryBuilder;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        try {
            $jobs = QueryBuilder::for(Job::class)
                ->allowedFilters(['name'])
                ->get();
            $success['data'] = JobResource::collection($jobs);
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
    public function store(StoreJobRequest $request)
    {
        try {
            $validated = $request->validated();
            $job = job::create([
                'name' => $validated['name'],
            ]);
            $success['data'] = $job;
            $success['message'] = 'job added successfully';
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
    public function show(Job $job)
    {
        try {
            $success['data'] = new JobResource($job);
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
    public function update(UpdateJobRequest $request, Job $job)
    {
        try {
            $job->update($request->validated());
            $success['data'] = new JobResource($job);
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
    public function destroy(Job $job)
    {
        try {
            $employee = QueryBuilder::for(Employee::class)
            ->where('job_hire', $job->id)
            ->orwhere('job_current', $job->id)
            ->first();

            if(!$employee){
                $job->delete();
                $success['message'] = 'Job Deleted successfully';
                $success['success'] = true;
                return response()->json($success, 200);
            }else{
                $success['message'] = 'This Job has employees, you cant deleted';
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
