<?php

namespace App\Jobs;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckExpiredContracts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Kiểm tra và xóa hợp đồng chưa thanh toán khi đến ngày bắt đầu học kỳ
        Contract::checkAndDeleteUnpaidContracts();

        // Kiểm tra hợp đồng hết hạn
        Contract::checkExpiredContracts();

        Log::info('Kiểm tra hợp đồng hết hạn và chưa thanh toán hoàn tất.');
    }
}
