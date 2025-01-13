<?php

namespace App\Service;

class TrendTemperatureService
{
    /**
     * Calculate the trend of the current temperature compared to the average temperature.
     *
     * @param float $currentTemp
     * @param float $averageTemp
     * @return string
     */
    public static function calculate(float $currentTemp, float $averageTemp): string
    {
        $trend = '-';
        if ($currentTemp > $averageTemp) {
            $trend = '🥵';
        } elseif ($currentTemp < $averageTemp) {
            $trend = '🥶';
        }

        return $trend;
    }
}
