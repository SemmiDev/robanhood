<?php

namespace App\Http\Controllers\Helpers;

class TimeBasedGreetingHelper
{
    private const MORNING_START = 5;
    private const AFTERNOON_START = 12;
    private const EVENING_START = 15;
    private const NIGHT_START = 18;

    /**
     * Get greeting based on current time
     *
     * @return string Returns appropriate greeting based on current hour
     */
    public function getGreeting(): string
    {
        // get hour of local time
        $hour = (int) date('H');

        return match (true) {
            $this->isMorning($hour) => 'Selamat Pagi',
            $this->isAfternoon($hour) => 'Selamat Siang',
            $this->isEvening($hour) => 'Selamat Sore',
            $this->isNight($hour) => 'Selamat Malam',
            default => 'Selamat Datang'
        };
    }

    /**
     * Check if current hour is morning time (05:00 - 11:59)
     */
    private function isMorning(int $hour): bool
    {
        return $hour >= self::MORNING_START && $hour < self::AFTERNOON_START;
    }

    /**
     * Check if current hour is afternoon time (12:00 - 14:59)
     */
    private function isAfternoon(int $hour): bool
    {
        return $hour >= self::AFTERNOON_START && $hour < self::EVENING_START;
    }

    /**
     * Check if current hour is evening time (15:00 - 17:59)
     */
    private function isEvening(int $hour): bool
    {
        return $hour >= self::EVENING_START && $hour < self::NIGHT_START;
    }

    /**
     * Check if current hour is night time (18:00 - 04:59)
     */
    private function isNight(int $hour): bool
    {
        return $hour >= self::NIGHT_START || $hour < self::MORNING_START;
    }
}
