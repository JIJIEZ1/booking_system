<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    $this->call([
        AdminSeeder::class,
        StaffSeeder::class,
    ]);
}

}
