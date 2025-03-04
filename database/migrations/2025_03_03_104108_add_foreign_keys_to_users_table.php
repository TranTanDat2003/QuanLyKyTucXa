<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->onDelete('set null')
                ->name('users_student_id_foreign');
            $table->foreign('staff_id')
                ->references('staff_id')
                ->on('staff')
                ->onDelete('set null')
                ->name('users_staff_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_student_id_foreign');
            $table->dropForeign('users_staff_id_foreign');
        });
    }
};
