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
        Schema::create('room_bills', function (Blueprint $table) {
            $table->id('room_bill_id');
            $table->foreignId('contract_id')->constrained('contracts', 'contract_id')->onDelete('cascade')->index();
            $table->string('student_id', 10)->index();
            $table->foreignId('semester_id')->constrained('semesters', 'semester_id')->onDelete('cascade')->index();
            $table->decimal('room_cost', 10, 2);
            $table->date('issue_date');
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
        Schema::dropIfExists('room_bills');
    }
};
