<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobHistoryResource extends JsonResource
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
            'id'=>$this->id,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'employee'=>$this->employee,
            'department'=>$this->department,
            'job'=>$this->job,
            'basic_salary'=>$this->basic_salary,
            'total_salary'=>$this->total_salary,

        ];
    }
}
