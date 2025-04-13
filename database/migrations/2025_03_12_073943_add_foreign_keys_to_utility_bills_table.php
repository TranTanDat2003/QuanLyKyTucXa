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
        Schema::table('utility_bills', function (Blueprint $table) {
            $table->foreign('utility_id')->references('utility_id')->on('utilities')->onDelete('cascade');
            $table->foreign('contract_id')->references('contract_id')->on('contracts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utility_bills', function (Blueprint $table) {
            $table->dropForeign('utility_bills_utility_id_foreign');
            $table->dropForeign('utility_bills_contract_id_foreign');
        });
    }
};
