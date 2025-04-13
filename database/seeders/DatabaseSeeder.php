<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạm tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Xóa dữ liệu các bảng
        Staff::truncate();
        Student::truncate();
        User::truncate();

        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo dữ liệu mẫu cho các bảng
        $faker = Faker::create();
        $faker->unique(true);

        // Gọi AdminSeeder
        $this->call(AdminSeeder::class);

        // Gọi UsersSeeder
        $this->call(UsersSeeder::class);
    }
}
