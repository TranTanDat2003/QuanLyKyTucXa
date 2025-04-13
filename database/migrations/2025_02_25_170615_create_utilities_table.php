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
            $table->date('month')->index();
            $table->decimal('electricity_reading', 10, 2)->default(0)->comment('Chỉ số điện (kWh)');
            $table->decimal('water_reading', 10, 2)->default(0)->comment('Chỉ số nước (m³)');
            $table->decimal('electricity_usage', 10, 2)->default(0)->comment('Lượng điện tiêu thụ (kWh)');
            $table->decimal('water_usage', 10, 2)->default(0)->comment('Lượng nước tiêu thụ (m³)');
            $table->decimal('utility_cost', 10, 2)->default(0)->comment('Tổng tiền');
            $table->unsignedBigInteger('room_id')->index();
            $table->unsignedBigInteger('rate_id')->index();
            $table->unsignedBigInteger('created_by')->nullable()->index()->comment('Tạo bởi nhân viên');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Thay đổi bởi nhân viên');
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
