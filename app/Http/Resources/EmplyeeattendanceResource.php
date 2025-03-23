<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmplyeeattendanceResource extends JsonResource
{

    private  $datefrom;
    private  $dateto;
   
    public function __construct($employee,?String $datefrom ,?String $dateto)
    {
        parent::__construct($employee);
        $this->datefrom = $datefrom;
        $this->dateto = $dateto;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,           
            'full_name'=>$this->first_name .' '.  $this->last_name,
            'jobcurrent'=>$this->jobcurrent->name ,
            'nationality'=>$this->nationality ,
            'attendances' => AttendanceResource::collection($this->attendances->where('Date','>=',$this->datefrom)->where('Date','<=',$this->dateto)),
          
          
            
        ];
    }
}
