<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    protected $primaryKey = 'utility_id';
    protected $fillable = ['room_id', 'month', 'electricity_usage', 'water_usage', 'electricity_cost', 'water_cost'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function utilityBills()
    {
        return $this->hasMany(UtilityBill::class, 'utility_id');
    }

    // Lấy danh sách tiện ích theo tháng
    public static function getUtilitiesByMonth($month)
    {
        return self::where('month', $month)->with('room')->get();
    }
}
