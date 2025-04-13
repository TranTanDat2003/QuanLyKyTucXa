<?php

namespace App\Observers;

use App\Models\Utility;
use App\Models\UtilityBill;
use App\Models\UtilityRate;

class UtilityObserver
{
    /**
     * Handle the Utility "saving" event.
     */
    public function saving(Utility $utility)
    {
        $rate = UtilityRate::find($utility->rate_id);
        if (!$rate) {
            throw new \Exception('Biểu giá không tồn tại');
        }

        // Kiểm tra chỉ số hợp lệ
        $utility->validateReadings($utility->electricity_reading, $utility->water_reading);

        // Tính toán usage và cost
        $utility->calculateUsageAndCost($utility->electricity_reading, $utility->water_reading, $rate);
    }

    /**
     * Handle the Utility "created" event.
     */
    public function created(Utility $utility): void
    {
        UtilityBill::createOrUpdateForUtility($utility);
    }

    /**
     * Handle the Utility "updated" event.
     */
    public function updated(Utility $utility): void
    {
        UtilityBill::createOrUpdateForUtility($utility);
    }

    /**
     * Handle the Utility "deleted" event.
     */
    public function deleted(Utility $utility): void
    {
        //
    }

    /**
     * Handle the Utility "restored" event.
     */
    public function restored(Utility $utility): void
    {
        //
    }

    /**
     * Handle the Utility "force deleted" event.
     */
    public function forceDeleted(Utility $utility): void
    {
        //
    }
}
