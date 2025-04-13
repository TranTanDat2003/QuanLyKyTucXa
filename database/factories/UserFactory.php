<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        fake()->unique(true);
        return [
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make(Str::password(8)),
            'role' => fake()->randomElement(['admin', 'staff', 'student']),
            'status' => 1,
        ];
    }

    public function staff()
    {
        return $this->state(function (array $attributes) {
            $username = $this->generateUniqueStaffUsername();
            return [
                'username' => $username,
                'role' => 'staff',
            ];
        });
    }

    public function student()
    {
        return $this->state(function (array $attributes) {
            $username = $this->generateUniqueStudentUsername();
            return [
                'username' => $username,
                'role' => 'student',
            ];
        });
    }

    private function generateUniqueStaffUsername()
    {
        do {
            $year = fake()->numberBetween(15, 23);
            $randomNumber = str_pad(fake()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
            $username = "00{$year}{$randomNumber}";
        } while (User::where('username', $username)->exists());

        return $username;
    }

    private function generateUniqueStudentUsername()
    {
        do {
            $year = fake()->numberBetween(21, 25);
            $randomNumber = str_pad(fake()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT);
            $username = "B{$year}{$randomNumber}";
        } while (User::where('username', $username)->exists());

        return $username;
    }
}
