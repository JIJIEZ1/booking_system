<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        Staff::create([
            'staff_id' => 'S001',
            'name' => 'Ali',
            'email' => 'ali@gmail.com',
            'phone' => '0123456789',
            'role' => 'Manager',
            'password' => Hash::make('123456'),
        ]);

        Staff::create([
            'staff_id' => 'S002',
            'name' => 'Fatimah',
            'email' => 'fatimah@gmail.com',
            'phone' => '0112233445',
            'role' => 'Assistant',
            'password' => Hash::make('654321'),
        ]);
    }
}
