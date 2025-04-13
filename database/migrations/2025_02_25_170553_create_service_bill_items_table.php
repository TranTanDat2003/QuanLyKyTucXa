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
        Schema::create('service_bill_items', function (Blueprint $table) {
            $table->id('service_bill_item_id');
            $table->unsignedBigInteger('service_bill_id')->index();
            $table->unsignedBigInteger('service_id')->index();
            $table->string('bike_plate')->nullable();
            $table->decimal('service_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->date('start_date')->index();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_bill_items');
    }
};
