<?php

namespace App\Helpers;

class DateFormatHelper
{
    public static function toEnglishDate(string $date): string
    {
        return self::indonesianToEnglishMonthName(self::indonesianToEnglishDayName($date));
    }

    public static function indonesianToEnglishDayName(string $day): string
    {
        return match(strtolower($day)) {
            "minggu" => "Sunday",
            "senin" => "Monday",
            "selasa" => "Tuesday",
            "rabu" => "Wednesday",
            "kamis" => "Thursday",
            "jumat" => "Friday",
            "sabtu" => "Saturday",
            default => $day,
        };
    }

    public static function indonesianToEnglishMonthName(string $month): string
    {
        return match(strtolower($month)) {
            "januari" => "January",
            "februari" => "February",
            "maret" => "March",
            "april" => "April",
            "mei" => "May",
            "juni" => "June",
            "juli" => "July",
            "agustus" => "August",
            "september" => "September",
            "oktober" => "October",
            "november" => "November",
            "desember" => "December",
            default => $month,
        };
    }
}
