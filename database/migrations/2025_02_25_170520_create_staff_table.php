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
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staff_id');
            $table->string('staff_code', 8)->unique()->index()->comment('Mã số nhân viên');
            $table->string('full_name', 50);
            $table->date('date_of_birth')->nullable();
            $table->boolean('gender')->comment('gender: 0 - Nam, 1 - Nữ');
            $table->string('phone', 10)->nullable();
            $table->string('address')->nullable();
            $table->string('email', 50)->unique();
            $table->string('image');
            $table->unsignedBigInteger('user_id')->unique()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
