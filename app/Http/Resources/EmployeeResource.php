<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mid_name' => $this->mid_name,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'nationality' => $this->nationality,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'gender' => $this->gender,
            'familty_status' => $this->familty_status,
            'child_number' => $this->child_number,
            'address_current' => $this->address_current,
            'address_permanent' => $this->address_permanent,
            'image' => $this->image,
            'department_hire' => $this->departmenthire,
            'department_current' => $this->departmentcurrent,
            'job_hire' => $this->jobhire,
            'job_current' => $this->jobcurrent,
            'company' => $this->company,
            'salary' => $this->salary,
            // 'salary_trasnportation'=>$this->salary_trasnportation,
            // 'salary_jobtype'=>$this->salary_jobtype,
            // 'salary_food'=>$this->salary_food,
            'total_salary' => $this->total_salary,
            'allowances' => $this->allowances,
            'visa_expiry' => $this->visa_expiry,
            'visa_validity' => $this->visa_validity,
            'hire_date' => $this->hire_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'address_incompany_id' => $this->addressincompany,
            'Job_history' => JobHistoryResource::collection($this->job_history),
            'cancelation_date' => $this->cancelation_date,
            'attendances' => AttendanceResource::collection($this->attendances),
            'finger' => $this->finger,
            'job_history'=>$this->job_history,
            'self_number' =>$this->self_number,
            'passport_number'=> $this->passport_number,
            'insurance_number'=> $this->insurance_number,
            'uaeid_number'=> $this->uaeid_number,
            'language'=> $this->language,
            'education'=> $this->education,
            'courses'=> $this->courses,
            'contract_type'=> $this->contract_type,
            'workcard_number'=> $this->workcard_number,
            'file_number'=>$this->file_number
        ];
    }
}
