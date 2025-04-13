<?php

namespace App\Rules;

use App\Models\Building;
use App\Models\Room;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class UniqueRoomCode implements ValidationRule
{
    protected $buildingId;
    protected $excludeId;

    public function __construct($buildingId, $excludeId = null)
    {
        $this->buildingId = $buildingId;
        $this->excludeId = $excludeId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->buildingId) {
            Log::warning('Building ID is null');
            return;
        }

        $building = Building::find($this->buildingId);

        if (!$building) {
            Log::warning('Building not found for ID:', ['building_id' => $this->buildingId]);
            return; // Nếu không tìm thấy building, bỏ qua kiểm tra
        }

        $fullRoomCode = $building->building_name . $value;

        $exists = Room::where('room_code', $fullRoomCode)
            ->where('room_id', '!=', $this->excludeId)
            ->exists();

        if ($exists) {
            $fail('Mã phòng này đã được sử dụng.');
        }
    }
}
