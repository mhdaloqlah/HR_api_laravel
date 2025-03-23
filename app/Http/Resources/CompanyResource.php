<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'companyName'=>$this->companyName,
            'image'=>$this->image,
            'license_number'=>$this->license_number,
            'license_release'=>$this->license_release,
            'license_expiry'=>$this->license_expiry,
            'phone1'=>$this->phone1,
            'phone2'=>$this->phone2,
            'fax'=>$this->fax,
            'email'=>$this->email,
            'website'=>$this->website,
            'about'=>$this->about,
            'location'=>$this->location,
            'address'=>$this->address,
            'facebook'=>$this->facebook,
            'twitter'=>$this->twitter,
            'linkden'=>$this->linkden,
            'skype'=>$this->skype,
            'whatsapp'=>$this->whatsapp,
            'instegram'=>$this->instegram,
            'status'=>$this->status,
            'employees_hire'=>EmployeeResource::collection($this->employees),
           
        ];
    }
}
