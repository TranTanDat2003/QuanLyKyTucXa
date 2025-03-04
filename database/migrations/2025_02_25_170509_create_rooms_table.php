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
            $table->string('room_name', 100)->unique();
            $table->string('room_code', 100)->unique();
            $table->enum('status', ['Đang sử dụng', 'Không sử dụng'])->default('Đang sử dụng')->index();
            $table->enum('gender', ['Nam', 'Nữ']);
            $table->foreignId('building_id')
                ->constrained('buildings', 'building_id')
                ->onDelete('cascade')
                ->index()
                ->name('rooms_building_id_foreign');
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
