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
        Schema::table('service_bills', function (Blueprint $table) {
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('semester_id')->references('semester_id')->on('semesters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_bills', function (Blueprint $table) {
            $table->dropForeign('service_bills_semester_id_foreign');
            $table->dropForeign('service_bills_student_id_foreign');
        });
    }
};
