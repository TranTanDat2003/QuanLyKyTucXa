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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id('contract_id');
            $table->string('student_id', 10)->index();
            $table->foreignId('room_id')->constrained('rooms', 'room_id')->onDelete('cascade')->index();
            $table->foreignId('semester_id')->constrained('semesters', 'semester_id')->onDelete('cascade')->index();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['Chờ duyệt', 'Đang thuê', 'Hết hạn', 'Hủy'])->default('Chờ duyệt')->index();
            $table->boolean('is_paid')->default(false);
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
