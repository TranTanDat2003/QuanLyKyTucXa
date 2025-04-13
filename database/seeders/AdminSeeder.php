<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo admin
        $adminUser = User::where('username', 'admin')->first();
        if (!$adminUser) {
            Staff::create([
                'staff_code' => 'admin',
                'full_name' => fake()->name(),
                'date_of_birth' => fake()->dateTimeBetween('-50 years', '-25 years')->format('Y-m-d'),
                'gender' => fake()->boolean(),
                'phone' => fake()->numerify('09########'),
                'address' => fake()->address(),
                'email' => 'admin@gmail.com',
                'image' => 'default_profile.jpg',
                'user_id' => 1,
            ]);
        }

        $adminUser = User::where('username', 'admin')->first();
        $adminUser->password = Hash::make('admin123');
        $adminUser->role = 'admin';
        $adminUser->save();
    }
}
