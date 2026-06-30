<?php

namespace App\Helpers;

use App\Models\Holiday;
use Carbon\Carbon;

class DeliveryDateHelper
{
    /**
     * Calculate delivery date based on the time an order is placed.
     * Rules:
     * - Before 1 PM: Target delivery is next working day at 8 AM.
     * - After 1 PM: Target delivery is day after next working day at 10 AM.
     * - Skips Sundays.
     * - Skips configured holidays.
     *
     * @param \Carbon\Carbon $orderTime
     * @return \Carbon\Carbon
     */
    public static function calculateDeliveryDate(Carbon $orderTime)
    {
        $targetTime = $orderTime->copy();
        
        $isBefore1PM = $targetTime->format('H:i') < '13:00';
        
        // Determine days to add initially
        $daysToAdd = $isBefore1PM ? 1 : 2;
        
        // Set specific delivery hour
        $deliveryHour = $isBefore1PM ? 8 : 10;
        
        $targetTime->setTime($deliveryHour, 0, 0);

        // Find holidays to skip
        $holidays = Holiday::pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        // Increment days
        for ($i = 0; $i < $daysToAdd; $i++) {
            $targetTime->addDay();
            
            // While target day is a Sunday or a holiday, keep adding days
            while ($targetTime->isSunday() || in_array($targetTime->format('Y-m-d'), $holidays)) {
                $targetTime->addDay();
            }
        }

        return $targetTime;
    }

    /**
     * Check if a given date is a holiday or Sunday.
     *
     * @param string|\Carbon\Carbon $date
     * @return bool
     */
    public static function isHolidayOrSunday($date)
    {
        $carbonDate = Carbon::parse($date);

        if ($carbonDate->isSunday()) {
            return true;
        }

        return Holiday::where('date', $carbonDate->format('Y-m-d'))->exists();
    }
}
