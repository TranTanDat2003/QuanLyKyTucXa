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
        Schema::create('utility_rates', function (Blueprint $table) {
            $table->id('rate_id');
            $table->decimal('electricity_rate', 10, 2);
            $table->decimal('water_rate', 10, 2);
            $table->date('effective_date')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_rates');
    }
};
