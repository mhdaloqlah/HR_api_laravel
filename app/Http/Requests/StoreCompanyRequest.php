<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreCompanyRequest extends FormRequest
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
            'companyName'=>'required|max:255|string',
            'license_number'=>'sometimes',
            'license_release'=>'sometimes',
            'license_expiry'=>'sometimes',
            'email'=>'sometimes',
            'phone1'=>'sometimes',
            'phone2'=>'sometimes',
            'fax'=>'sometimes',
            'website'=>'sometimes',
            'about'=>'sometimes',
            'location'=>'sometimes',
            'address'=>'sometimes',
            'facebook'=>'sometimes',
            'whatsapp'=>'sometimes',
            'linkden'=>'sometimes',
            'skype'=>'sometimes',
            'twitter'=>'sometimes',
            'instegram'=>'sometimes',
            'status'=>'sometimes',
            'image' => 'sometimes|file',
           
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
