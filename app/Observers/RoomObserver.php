<?php

namespace App\Observers;

use App\Models\Room;

class RoomObserver
{
    /**
     * Handle the Room "created" event.
     */
    public function created(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "creating" event.
     */
    public function creating(Room $room): void
    {
        $roomType = $room->roomType;
        $room->available_slots = $roomType ? $roomType->capacity : 0;
    }

    /**
     * Handle the Room "updated" event.
     */
    public function updated(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "deleted" event.
     */
    public function deleted(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "restored" event.
     */
    public function restored(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "force deleted" event.
     */
    public function forceDeleted(Room $room): void
    {
        //
    }
}
