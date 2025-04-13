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
        Schema::create('service_bills', function (Blueprint $table) {
            $table->id('service_bill_id');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('paid_at')->nullable();
            $table->date('issued_date');
            $table->date('due_date');
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('semester_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_bills');
    }
};
