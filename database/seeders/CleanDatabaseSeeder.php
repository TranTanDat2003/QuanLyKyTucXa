<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Cleaning database...');

        // Tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Lấy tất cả các bảng
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            if ($tableName != 'migrations') { // Giữ lại bảng migrations
                DB::statement("TRUNCATE TABLE `$tableName`");
                $this->command->info("Truncated: $tableName");
            }
        }

        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('Database cleaning completed!');
    }
}
