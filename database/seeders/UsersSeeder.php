<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        Student::factory()->count(5)->createQuietly();
        Staff::factory()->count(5)->createQuietly();
    }
}
