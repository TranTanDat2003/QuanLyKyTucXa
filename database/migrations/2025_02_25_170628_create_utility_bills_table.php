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
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id('utility_bill_id');
            $table->foreignId('utility_id')->constrained('utilities', 'utility_id')->onDelete('cascade')->index();
            $table->string('student_id', 10)->index();
            $table->decimal('electricity_cost', 10, 2)->nullable();
            $table->decimal('water_cost', 10, 2)->nullable();
            $table->decimal('service_cost', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->storedAs('COALESCE(electricity_cost, 0) + COALESCE(water_cost, 0) + COALESCE(service_cost, 0)');
            $table->date('issue_date')->index();
            $table->date('due_date')->nullable();
            $table->enum('status', ['Chưa thanh toán', 'Đã thanh toán'])->default('Chưa thanh toán')->index();
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_bills');
    }
};
