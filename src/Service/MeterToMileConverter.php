<?php

namespace App\Service;

class MeterToMileConverter
{
    public static function convertToMiles($value)
    {
        return round(($value/1609),2);
    }
}