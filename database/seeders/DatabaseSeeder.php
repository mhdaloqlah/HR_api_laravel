<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    { 
        $this->call(CompanySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(JobSeeder::class);
        $this->call(AddressSeeder::class);
        // $this->call(EmployeeSeeder::class);
        // $this->call(JobHistorySeeder::class);
        
        // \App\Models\User::factory(10)->create();

       
    }
}
