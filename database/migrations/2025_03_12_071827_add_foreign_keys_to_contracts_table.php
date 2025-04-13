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
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
            $table->foreign('room_type_id')->references('room_type_id')->on('room_types')->onDelete('cascade');
            $table->foreign('semester_id')->references('semester_id')->on('semesters')->onDelete('cascade');
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign('contracts_room_id_foreign');
            $table->dropForeign('contracts_room_type_id_foreign');
            $table->dropForeign('contracts_semester_id_foreign');
            $table->dropForeign('contracts_staff_id_foreign');
            $table->dropForeign('contracts_student_id_foreign');
        });
    }
};
