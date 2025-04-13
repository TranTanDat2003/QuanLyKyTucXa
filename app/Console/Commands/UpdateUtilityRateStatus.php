<?php

namespace App\Console\Commands;

use App\Models\UtilityRate;
use Illuminate\Console\Command;

class UpdateUtilityRateStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-utility-rate-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái hiệu lực của giá tiện ích dựa trên ngày hiện tại';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            UtilityRate::updateStatus();
            $this->info('Trạng thái giá tiện ích đã được cập nhật thành công!');
        } catch (\Exception $e) {
            $this->error('Có lỗi xảy ra khi cập nhật trạng thái giá điện nước: ' . $e->getMessage());
        }
    }
}
