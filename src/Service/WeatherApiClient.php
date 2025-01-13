<?php

namespace App\Service;

class WeatherApiClient
{
    /**
     * Fetches the current temperature and the last 10 days temperatures for a given city.
     *
     * @throws \Exception
     */
    public function fetchTemperature(string $city): array
    {
        $data = [
            'Sofia'     =>  [
                'current'         => rand(-10, 10),
                'lastTenDays' => self::getRandomTemperature()
            ],
            'London' => [
                'current'         => rand(-10, 10),
                'lastTenDays' => self::getRandomTemperature()
            ],
            'Paris'     => [
                'current'         => rand(-10, 10),
                'lastTenDays' => self::getRandomTemperature()
            ],
        ];

        return $data[$city] ?? throw new \Exception('City not found');
    }

    /**
     * Returns an array with random temperatures for the last 10 days.
     *
     * @return array
     */
    private static function getRandomTemperature(): array
    {
        $range = range(1, 10);
        return array_combine(
            array_map(fn($index) => date('Y-m-d', strtotime("-$index days")), $range),
            array_map(fn()            => rand(-10, 10), $range)
        );
    }
}