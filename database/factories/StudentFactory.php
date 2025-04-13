<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $user = User::factory()->student()->create();

        return [
            'student_code' => $user->username,
            'full_name' => fake()->name(),
            'date_of_birth' => fake()->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
            'gender' => fake()->boolean(),
            'phone' => fake()->numerify('0#########'),
            'address' => fake()->address(),
            'email' => fake()->unique()->safeEmail(),
            'major' => fake()->randomElement(['CNTT', 'Kinh tế', 'Cơ khí', 'Điện tử']),
            'class' => fake()->regexify('[A-Z]{2}[0-9]{2}'),
            'enrollment_year' => fake()->numberBetween(2021, 2025),
            'image' => 'default_profile.jpg',
            'user_id' => $user->id,
        ];
    }
}
