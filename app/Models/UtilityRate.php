<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UtilityRate extends Model
{
    protected $table = 'utility_rates';
    protected $primaryKey = 'rate_id';

    protected $fillable = [
        'electricity_rate',
        'water_rate',
        'effective_date',
        'status',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'status' => 'boolean',
    ];

    public function utilities()
    {
        return $this->hasMany(Utility::class, 'rate_id');
    }

    // Lấy bản ghi hiệu lực hiện tại
    public static function getCurrentRate()
    {
        return self::where('effective_date', '<=', now())
            ->orderBy('effective_date', 'desc')
            ->first();
    }

    // Lấy bản ghi hiệu lực hiện tại
    public static function getRateActive()
    {
        return self::where('status', 1)->first();
    }

    // Cập nhật status để chỉ có một bản ghi là hiệu lực
    public static function updateStatus()
    {
        DB::transaction(function () {
            // Lấy bản ghi có effective_date gần nhất trước hoặc bằng ngày hiện tại
            $currentRate = self::getCurrentRate();
            $currentActive = self::getRateActive();

            // Nếu bản ghi hiện tại đã có status = 1 và không thay đổi, không cần cập nhật
            if ($currentActive && $currentRate && $currentActive->rate_id === $currentRate->rate_id) {
                return;
            }

            // Đặt tất cả status về 0
            self::query()->update(['status' => 0]);

            // Nếu có bản ghi hiện tại, đặt status = 1
            if ($currentRate) {
                $currentRate->update(['status' => 1]);
            }
        });
    }
}
