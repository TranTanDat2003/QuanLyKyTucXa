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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->text('content')->comment('Nội dung báo cáo lỗi');
            $table->string('image')->nullable()->comment('Ảnh minh họa lỗi');
            $table->date('scheduled_fix_date')->nullable()->comment('Ngày hẹn sửa chữa');
            $table->tinyInteger('quantity')->comment('Số lượng lỗi');
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->comment('Trạng thái: pending - chờ duyệt, approved - đã duyệt, rejected - từ chối');
            $table->unsignedBigInteger('room_id')->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('staff_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
