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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->foreignId('building_id')->constrained('buildings', 'building_id')->onDelete('cascade')->index();
            $table->string('room_number', 10);
            $table->integer('capacity');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['Trống', 'Đầy', 'Đang sửa'])->default('Trống')->index();
            $table->enum('gender', ['Nam', 'Nữ', 'Cả hai'])->default('Cả hai');
            $table->boolean('allow_cooking')->default(false);
            $table->boolean('has_air_conditioner')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
