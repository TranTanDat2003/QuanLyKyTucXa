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
        Schema::create('utilities', function (Blueprint $table) {
            $table->id('utility_id');
            $table->foreignId('room_id')->constrained('rooms', 'room_id')->onDelete('cascade')->index();
            $table->date('month')->index();
            $table->decimal('electricity_usage', 10, 2)->default(0);
            $table->decimal('water_usage', 10, 2)->default(0);
            $table->decimal('electricity_cost', 10, 2)->nullable();
            $table->decimal('water_cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilities');
    }
};
