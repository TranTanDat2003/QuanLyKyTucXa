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
        Schema::table('service_bill_items', function (Blueprint $table) {
            $table->foreign('service_bill_id')->references('service_bill_id')->on('service_bills')->onDelete('cascade');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_bill_items', function (Blueprint $table) {
            $table->dropForeign('service_bill_items_service_bill_id_foreign');
            $table->dropForeign('service_bill_items_service_id_foreign');
        });
    }
};
