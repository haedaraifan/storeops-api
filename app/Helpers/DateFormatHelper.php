<?php

namespace App\Helpers;

class DateFormatHelper
{
    public static function indonesianToEnglishDayName(string $day): string
    {
        $weekdaysMap = [
            "minggu" => "Sunday",
            "senin" => "Monday",
            "selasa" => "Tuesday",
            "rabu" => "Wednesday",
            "kamis" => "Thursday",
            "jumat" => "Friday",
            "sabtu" => "Saturday",
        ];

        $loweredcaseDay = strtolower($day);

        if (array_key_exists($loweredcaseDay, $weekdaysMap)) {
            return $weekdaysMap[$loweredcaseDay];
        }
        return $day;
    }
}
