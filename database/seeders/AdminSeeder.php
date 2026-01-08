<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('administrator')->insert([
            'admin_id' => 'A001',
            'name' => 'Admin One',
            'email' => 'azizrahim355@gmail.com',
            'password' => Hash::make('admin123'),
            'phone_number' => '0123456789',
        ]);
    }
}
