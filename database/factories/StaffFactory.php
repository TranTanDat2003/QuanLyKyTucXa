<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        $user = User::factory()->staff()->create();

        return [
            'staff_code' => $user->username, // Đồng bộ với username
            'full_name' => fake()->name(),
            'date_of_birth' => fake()->dateTimeBetween('-50 years', '-25 years')->format('Y-m-d'),
            'gender' => fake()->boolean(),
            'phone' => fake()->numerify('09########'),
            'address' => fake()->address(),
            'email' => fake()->unique()->safeEmail(),
            'image' => 'default_profile.jpg',
            'user_id' => $user->id,
        ];
    }
}
