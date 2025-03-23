<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'first_name'=>'Mohammad',
            'last_name'=>'Aloqlah',
            'department_hire'=>'1',
            'department_current'=>'1',
            'job_hire'=>'1',
            'job_current'=>'1',
            'company_id'=>'1',
            'address_incompany_id'=>'2',
            'hire_date'=>'2024-04-04',
            'salary'=>4000,
            'total_salary'=>4500
        ]);

        Employee::create([
            'first_name'=>'Sidra',
            'last_name'=>'Mhanna',
            'department_hire'=>'1',
            'department_current'=>'1',
            'job_hire'=>'2',
            'job_current'=>'2',
            'company_id'=>'1',
            'address_incompany_id'=>'1',
            'hire_date'=>'2024-04-04',
            'salary'=>4000,
            'total_salary'=>4500
        ]);
    }
}
