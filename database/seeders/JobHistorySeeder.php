<?php

namespace Database\Seeders;

use App\Models\Job_history;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Job_history::create([
            'start_date'=>'2024-07-10',
           
            'job_id'=>'1',
            'employee_id'=>'1',
            'department_id'=>'1',
        ]);
        Job_history::create([
            'start_date'=>'2024-07-10',
            // 'end_date'=>'null',
            'job_id'=>'2',
            'employee_id'=>'2',
            'department_id'=>'1',
        ]);
    }
}
