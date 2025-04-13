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
            $table->string('room_code', 100)->unique();
            $table->integer('available_slots')->default(0);
            $table->enum('status', ['Đang sử dụng', 'Không sử dụng', 'Đang sửa chữa'])->default('Đang sử dụng')->index();
            $table->enum('gender', ['Nam', 'Nữ']);
            $table->unsignedBigInteger('building_id')->index();
            $table->unsignedBigInteger('room_type_id')->index();
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
