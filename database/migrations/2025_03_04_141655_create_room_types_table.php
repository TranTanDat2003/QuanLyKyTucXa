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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id('room_type_id');
            $table->string('room_type_name', 50);
            $table->integer('capacity');
            $table->boolean('has_air_conditioner')->default(false);
            $table->boolean('allow_cooking')->default(false);
            $table->decimal('room_type_price', 10, 2);
            $table->string('room_type_img_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
