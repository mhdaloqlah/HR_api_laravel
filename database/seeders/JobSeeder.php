<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Job::create([
            'name'=>'IT manager'
        ]);
        Job::create([
            'name'=>'technical suuport'
        ]);
        Job::create([
            'name'=>'cashier'
        ]);
    }
}
