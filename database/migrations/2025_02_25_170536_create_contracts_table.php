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
            $table->date('contract_start_date');
            $table->date('contract_end_date');
            $table->date('actual_end_date')->nullable();
            $table->timestamp('approve_at')->nullable();
            $table->enum('status', ['Chờ duyệt', 'Đã duyệt', 'Đang ở', 'Hết hạn', 'Hủy'])->default('Chờ duyệt')->index();
            $table->decimal('contract_cost', 10, 2)->default(0)->comment('Giá hợp đồng');
            $table->decimal('paid_amount', 10, 2)->default(0)->comment('Số tiền đã thanh toán');
            $table->boolean('is_paid')->default(false);
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('staff_id')->nullable()->index();
            $table->unsignedBigInteger('room_type_id')->index();
            $table->unsignedBigInteger('room_id')->nullable()->index();
            $table->unsignedBigInteger('semester_id')->index();
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
