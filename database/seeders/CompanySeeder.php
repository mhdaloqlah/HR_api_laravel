<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'companyName'=>'Sama Ezdan',
            'email'=>'sama@gmail.com',
            'phone1'=>'544545454',
            'location'=>'Dubai',
            'status'=>0
        ]);
        Company::create([
            'companyName'=>'Sedcobc',
            'email'=>'Sedcobc@gmail.com',
            'phone1'=>'3355666',
            'location'=>'Dubai',
            'status'=>1
        ]);
    }
}
