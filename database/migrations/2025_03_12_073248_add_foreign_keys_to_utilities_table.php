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
        Schema::table('utilities', function (Blueprint $table) {
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
            $table->foreign('rate_id')->references('rate_id')->on('utility_rates')->onDelete('restrict');
            $table->foreign('created_by')->references('staff_id')->on('staff')->onDelete('set null');
            $table->foreign('updated_by')->references('staff_id')->on('staff')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilities', function (Blueprint $table) {
            $table->dropForeign('utilities_room_id_foreign');
            $table->dropForeign('utilities_rate_id_foreign');
            $table->dropForeign('utilities_created_by_foreign');
            $table->dropForeign('utilities_updated_by_foreign');
        });
    }
};
