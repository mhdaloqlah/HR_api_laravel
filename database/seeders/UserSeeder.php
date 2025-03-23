<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
       $user = User::create([
        'name' => 'Test User',
        'email' => 'admin@hr.com',
        'password'=>Hash::make('admin2024')

       ]);

       $user->createToken('api_token')->plainTextToken;
        
    }
}
