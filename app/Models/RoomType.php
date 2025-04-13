<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $primaryKey = 'room_type_id';
    protected $fillable = [
        'room_type_name',
        'capacity',
        'has_air_conditioner',
        'allow_cooking',
        'room_type_price',
        'room_type_img_path'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }

    public function getRooms()
    {
        return $this->rooms();
    }

    public static function filterRoomTypes($capacity = null, $amenities = null, $semesterId = null)
    {
        $query = self::query()->withCount(['rooms' => function ($q) use ($semesterId) {
            $q->where('status', 'Đang sử dụng')
                ->withCount(['contracts' => function ($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)->where('status', 'Đang ở');
                }]);
        }]);

        if ($capacity) {
            $query->where('capacity', $capacity);
        }

        if ($amenities) {
            if ($amenities === 'ac') {
                $query->where('has_air_conditioner', 1);
            } elseif ($amenities === 'cooking') {
                $query->where('allow_cooking', 1);
            } elseif ($amenities === 'both') {
                $query->where('has_air_conditioner', 1)->where('allow_cooking', 1);
            }
        }

        return $query->get()->map(function ($roomType) use ($semesterId) {
            $totalCapacity = $roomType->rooms->sum('room_type.capacity');
            $occupiedSlots = $roomType->rooms->sum('contracts_count');
            $roomType->available_slots = max(0, $totalCapacity - $occupiedSlots);
            return $roomType;
        });
    }

    // Lọc các loại phòng và kiểm tra số chỗ trống theo giới tính
    public static function filterRoomTypesWithAvailableSlots($capacity = null, $amenities = null, $gender = null)
    {
        $query = self::query()->with(['rooms' => function ($q) {
            $q->where('status', 'Đang sử dụng');
        }]);

        if ($capacity) {
            $query->where('capacity', $capacity);
        }

        // Lọc theo tiện ích (amenities) nếu có
        if ($amenities) {
            if ($amenities === 'ac') {
                $query->where('has_air_conditioner', 1);
            } elseif ($amenities === 'cooking') {
                $query->where('allow_cooking', 1);
            } elseif ($amenities === 'both') {
                $query->where('has_air_conditioner', 1)->where('allow_cooking', 1);
            }
        }

        return $query->get()->map(function ($roomType) use ($gender) {
            if ($gender) {
                $roomType->available_slots = $roomType->availableSlotsByGender($gender);
                $roomType->has_available = $roomType->hasAvailableSlotsByGender($gender);
            } else {
                $roomType->available_slots = $roomType->rooms->sum('available_slots');
                $roomType->has_available = $roomType->available_slots > 0;
            }
            return $roomType;
        });
    }

    // Lấy danh sách các phòng trống theo giới tính
    public function availableSlotsByGender($gender)
    {
        return $this->rooms()
            ->where('gender', $gender)
            ->where('status', 'Đang sử dụng')
            ->sum('available_slots');
    }

    // Kiểm tra xem còn phòng trống nào theo giới tính không
    public function hasAvailableSlotsByGender($gender)
    {
        return $this->availableSlotsByGender($gender) > 0;
    }
}
