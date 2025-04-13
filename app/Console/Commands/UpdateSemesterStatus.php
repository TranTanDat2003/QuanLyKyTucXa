<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Semester;
use Carbon\Carbon;

class UpdateSemesterStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-semester-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái học kỳ dựa trên ngày hiện tại';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $now = Carbon::now();
            $semesters = Semester::all();

            foreach ($semesters as $semester) {
                $status = ($now->gte($semester->start_date) && $now->lte($semester->end_date)) ? 1 : 0;
                if ($semester->status !== $status) {
                    $semester->update(['status' => $status]);
                    $this->info("Đã cập nhật trạng thái học kỳ {$semester->semester_name} thành " . ($status ? 'Hoạt động' : 'Không hoạt động'));
                }
            }

            $this->info('Cập nhật trạng thái học kỳ hoàn tất!');
        } catch (\Exception $e) {
            $this->error('Có lỗi xảy ra khi cập nhật trạng thái học kỳ: ' . $e->getMessage());
        }
    }
}
