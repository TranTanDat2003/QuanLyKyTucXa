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
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('building_id')->references('building_id')->on('buildings')->onDelete('cascade');
            $table->foreign('room_type_id')->references('room_type_id')->on('room_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign('rooms_building_id_foreign');
            $table->dropForeign('rooms_room_type_id_foreign');
        });
    }
};
