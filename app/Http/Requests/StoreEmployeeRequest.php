<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|max:255|string',
            'last_name' => 'sometimes|max:255|string',
            'image' => 'sometimes|file',
            'department_hire' => 'sometimes',
            'department_current' => 'sometimes',
            'job_hire' => 'sometimes',
            'job_current' => 'sometimes',
            'company_id' => 'sometimes',
            'mid_name' => 'sometimes',
            'father_name' => 'sometimes',
            'mother_name' => 'sometimes',
            'birth_date' => 'sometimes|date',
            'birth_place' => 'sometimes',
            'nationality' => 'sometimes',
            'phone' => 'sometimes',
            'mobile' => 'sometimes',
            'email' => 'sometimes|email',
            'gender' => 'sometimes',
            'familty_status' => 'sometimes',
            'child_number' => 'sometimes',
            'address_current' => 'sometimes',
            'address_permanent' => 'sometimes',
            'salary' => 'sometimes',
            // 'salary_trasnportation' => 'sometimes',
            // 'salary_jobtype' => 'sometimes',
            // 'salary_food' => 'sometimes',
            'hire_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'status' => 'sometimes',
            'total_salary' => 'sometimes',
            'address_incompany_id' => 'sometimes',
            'allowances' => 'sometimes',
            'visa_expiry' => 'sometimes',
            'visa_validity' => 'sometimes',
            'cancelation_date' => 'sometimes',
            'self_number' => 'sometimes',
            'passport_number' => 'sometimes' ,
            'insurance_number'=> 'sometimes',
            'uaeid_number'=> 'sometimes',
            'language'=> 'sometimes',
            'education'=> 'sometimes',
            'courses'=> 'sometimes',
            'contract_type'=> 'sometimes',
            'workcard_number'=> 'sometimes',
            'file_number'=> 'sometimes',
            'finger'=>'sometimes'

        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

            'success'   => false,
            'message'   => 'Validation errors',
            'data'  => $validator->errors()

        ]));
    }
}
