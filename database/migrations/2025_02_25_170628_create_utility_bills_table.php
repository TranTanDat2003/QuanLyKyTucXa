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
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id('utility_bill_id');
            $table->decimal('share_amount', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->date('paid_at')->nullable();
            $table->boolean('is_paid')->default(false)->comment('is_paid: 0 - chưa thanh toán, 1 : đã thanh toán');
            $table->unsignedBigInteger('utility_id')->index();
            $table->unsignedBigInteger('contract_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_bills');
    }
};
