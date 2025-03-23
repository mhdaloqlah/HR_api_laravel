<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\QueryBuilder\QueryBuilder;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'mid_name',
        'father_name',
        'mother_name',
        'birth_date',
        'birth_place',
        'nationality',
        'phone',
        'mobile',
        'email',
        'gender',
        'familty_status',
        'child_number',
        'address_current',
        'address_permanent',
        'image',
        'department_hire',
        'department_current',
        'job_hire',
        'job_current',
        'salary',
        'hire_date',
        'end_date',
        'status',
        'company_id',
        'address_incompany_id',
        'total_salary',
        'allowances',
        'visa_expiry',
        'visa_validity',
        'finger',
        'cancelation_date',
        'self_number',
        'passport_number',
        'insurance_number',
        'uaeid_number',
        'language',
        'education',
        'courses',
        'contract_type',
        'workcard_number',
        'file_number'
    ];
    public function departmenthire(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_hire');
    }
    public function departmentcurrent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_current');
    }

    public function jobhire(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_hire');
    }
    public function jobcurrent(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_current');
    }

    public function job_history(): HasMany
    {
        return $this->hasMany(Job_history::class, 'employee_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function addressincompany(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_incompany_id');
    }

    public function scopeTotalSalaryBetween($query, $salary_from, $salary_to): Builder
    {
        return $query->where('total_salary', '>=', $salary_from)
            ->where('total_salary', '<=', $salary_to);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }
}
