<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    protected $table = 'utilities';
    protected $primaryKey = 'utility_id';

    protected $fillable = [
        'month',
        'electricity_reading',
        'water_reading',
        'electricity_usage',
        'water_usage',
        'utility_cost',
        'room_id',
        'rate_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = ['month' => 'date'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function rate()
    {
        return $this->belongsTo(UtilityRate::class, 'rate_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Staff::class, 'updated_by');
    }

    public function utilityBills()
    {
        return $this->hasMany(UtilityBill::class, 'utility_id');
    }

    // Lấy bản ghi trước đó của phòng
    public function getPreviousUtility()
    {
        return self::where('room_id', $this->room_id)
            ->where('month', '<', $this->month)
            ->orderBy('month', 'desc')
            ->first();
    }

    // Kiểm tra chỉ số hợp lệ so với bản ghi trước
    public function validateReadings($electricityReading, $waterReading)
    {
        $previousUtility = $this->getPreviousUtility();

        if ($previousUtility) {
            if ($electricityReading < $previousUtility->electricity_reading) {
                throw new \Exception('Chỉ số điện không được nhỏ hơn chỉ số tháng trước (' . $previousUtility->electricity_reading . ' kWh)');
            }
            if ($waterReading < $previousUtility->water_reading) {
                throw new \Exception('Chỉ số nước không được nhỏ hơn chỉ số tháng trước (' . $previousUtility->water_reading . ' m³)');
            }
        }

        return true;
    }

    // Tính toán usage và cost
    public function calculateUsageAndCost($electricityReading, $waterReading, $rate)
    {
        $previousUtility = $this->getPreviousUtility();

        $this->electricity_usage = $previousUtility
            ? max(0, $electricityReading - $previousUtility->electricity_reading)
            : $electricityReading;
        $this->water_usage = $previousUtility
            ? max(0, $waterReading - $previousUtility->water_reading)
            : $waterReading;
        $this->utility_cost = ($this->electricity_usage * $rate->electricity_rate) + ($this->water_usage * $rate->water_rate);
    }

    // Lấy utilities theo room_id
    public static function getByRoomId($roomId)
    {
        return self::where('room_id', $roomId)
            ->with(['createdBy', 'updatedBy'])
            ->get();
    }

    // Tính tổng chi phí tiện ích
    public function getUtilityCostAttribute()
    {
        if (!$this->rate) return 0; // Tránh lỗi nếu rate_id không hợp lệ
        $electricityCost = $this->electricity_usage * $this->rate->electricity_rate;
        $waterCost = $this->water_usage * $this->rate->water_rate;
        return $electricityCost + $waterCost;
    }

    // Tính usage từ reading
    public function calculateUsage()
    {
        $previousUtility = $this->getPreviousUtility();

        if ($previousUtility) {
            $this->electricity_usage = max(0, $this->electricity_reading - $previousUtility->electricity_reading);
            $this->water_usage = max(0, $this->water_reading - $previousUtility->water_reading);
        } else {
            $this->electricity_usage = $this->electricity_reading;
            $this->water_usage = $this->water_reading;
        }

        $this->save();
    }

    // Lấy danh sách tiện ích theo tháng (hoặc tất cả nếu không có tháng)
    public static function getUtilities($month = null)
    {
        $query = self::with('room');
        if ($month) {
            $query->where('month', $month);
        }
        return $query->get();
    }

    // Lấy danh sách các tháng duy nhất
    public static function getUniqueMonths()
    {
        return self::select('month')->distinct()->orderBy('month')->pluck('month')->all();
    }
}
