<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CheckExpiredContracts;
use App\Console\Commands\UpdateSemesterStatus;
use App\Console\Commands\UpdateUtilityRateStatus;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kiểm tra hợp đồng hết hạn hàng ngày lúc 00:00
Schedule::job(new CheckExpiredContracts())->daily();

// Cập nhật trạng thái hiệu lực của học kỳ hàng ngày lúc 00:05
Schedule::command(UpdateSemesterStatus::class)->dailyAt('00:05');
// php artisan app:update-semester-status

// Cập nhật trạng thái hiệu lực của giá tiện ích hàng ngày lúc 00:10
Schedule::command(UpdateUtilityRateStatus::class)->dailyAt('00:10');
// php artisan app:update-utility-rate-status

// Schedule::command('app:clear-cache')->dailyAt('00:02')->emailOutputOnFailure();

// * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
// * * * * * php artisan schedule:run >> /dev/null 2>&1