<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $start_time = new DateTime($this->CheckInTime);
        $end_time = new DateTime($this->CheckOutTime); 
         $working_hours='00:00';
        if ($this->CheckInTime && $this->CheckOutTime)
            $working_hours =  $end_time->diff($start_time)->format('%H:%I');
       
      

        return [
            'id' => $this->id,
            'date' => $this->Date,
            'CheckInTime' => $this->CheckInTime,
            'CheckOutTime' => $this->CheckOutTime,
            'Status' => $this->Status,
            'workingHours' =>  $working_hours

        ];
    }
}
