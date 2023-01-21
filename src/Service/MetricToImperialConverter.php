<?php

namespace App\Service;

class MetricToImperialConverter
{
    public static function convertMetersToMiles($value)
    {
        return round(($value/1609),2);
    }

    public static function convertMetersToFeet($value)
    {
        return round(($value*3.28084));
    }
}