<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'address_name'=>'Bulding 1 room1'
        ]);
        Address::create([
            'address_name'=>'Bulding 1 room2'
        ]);
    }
}
