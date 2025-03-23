<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
       
    ];

    public function employees_job_hire():HasMany{
        return $this->hasMany(Employee::class,'job_hire');
    }

    public function employees_job_current():HasMany{
        return $this->hasMany(Employee::class,'job_current');
    }

    public function job_history():HasMany{
        return $this->hasMany(Job_history::class,'employee_id');
    }
}
