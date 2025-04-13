<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';

    protected $fillable = [
        'room_code',
        'status',
        'gender',
        'building_id',
        'room_type_id',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'room_id');
    }

    public function utilities()
    {
        return $this->hasMany(Utility::class, 'room_id');
    }

    public static function getAvailableRooms()
    {
        return self::where('status', 'Đang sử dụng')
            ->where('available_slots', '>', 0)
            ->with(['building', 'roomType'])
            ->get();
    }

    // Tính số chỗ trống cho học kỳ hiện tại
    public function getAvailableSlotsAttribute()
    {
        $currentSemester = Semester::getCurrentSemester();

        if (!$currentSemester) {
            return $this->roomType->capacity;
        }

        $capacity = $this->roomType->capacity;
        $activeContracts = $this->contracts()
            ->where('status', 'Đang ở')
            ->where('semester_id', $currentSemester->semester_id)
            ->count();

        return max(0, $capacity - $activeContracts);
    }

    // Tính số chỗ trống cho học kỳ cụ thể
    public function availableSlotsForSemester($semesterId)
    {
        $capacity = $this->roomType->capacity;
        $activeContracts = $this->contracts()
            ->where('status', 'Đang ở')
            ->where('semester_id', $semesterId)
            ->count();

        return max(0, $capacity - $activeContracts);
    }

    public static function getRoomsWithRelations()
    {
        return self::with(['roomType', 'building'])->get();
    }

    // Cập nhật số chỗ trống của phòng dựa trên hợp đồng
    public function updateAvailableSlots(): void
    {
        $this->available_slots = $this->roomType->capacity - $this->contracts()
            ->whereIn('status', ['Đã duyệt', 'Đang ở'])
            ->count();
        $this->save();
    }

    public static function findAvailableRoomByFillRatio($roomTypeId, $gender)
    {
        return self::where('rooms.room_type_id', $roomTypeId)
            ->where('gender', $gender)
            ->where('status', 'Đang sử dụng')
            ->where('available_slots', '>', 0)
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.room_type_id')
            ->select('rooms.*')
            ->orderByRaw('(room_types.capacity - rooms.available_slots) / room_types.capacity DESC')
            ->first();
    }
}
