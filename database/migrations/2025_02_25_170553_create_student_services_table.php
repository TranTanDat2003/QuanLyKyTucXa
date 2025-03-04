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
        Schema::create('student_services', function (Blueprint $table) {
            $table->id('student_service_id');
            $table->string('student_id', 10)->index();
            $table->foreignId('service_id')
                ->constrained('services', 'service_id')
                ->onDelete('cascade')
                ->index()
                ->name('student_services_service_id_foreign');
            $table->date('start_date')->index();
            $table->date('end_date')->nullable();
            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->onDelete('cascade')
                ->name('student_services_student_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_services');
    }
};
