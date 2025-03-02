<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $primaryKey = 'room_id';
    protected $fillable = ['building_id', 'room_number', 'capacity', 'price', 'status', 'gender', 'allow_cooking', 'has_air_conditioner'];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'room_id');
    }

    // Lấy danh sách phòng trống
    public static function getAvailableRooms()
    {
        return self::where('status', 'Trống')->with('building')->get();
    }
}
