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
        Schema::create('students', function (Blueprint $table) {
            $table->string('student_id', 10)->primary();
            $table->string('full_name', 50);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Nam', 'Nữ'])->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email', 50)->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
