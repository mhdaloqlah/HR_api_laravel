<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employeeview 
{

    public function __construct(
    public Employee $emp,
    public  $job_history =[]){
    }


}
