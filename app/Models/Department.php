<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',       
    ];

    public function employees_hire():HasMany{
        return $this->hasMany(Employee::class,'department_hire');
    }

    public function employees_current():HasMany{
        return $this->hasMany(Employee::class,'department_current');
    }

    public function job_history():HasMany{
        return $this->hasMany(Job_history::class,'department_id');
    }

   
}
