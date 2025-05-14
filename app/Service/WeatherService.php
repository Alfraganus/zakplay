<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    const BASE_URL = "https://api.weatherapi.com/v1/";
    const API_KEY = "bd27339d05a6410a836102102251405";

    /**
     * Get current weather by city.
     */
    public function getCurrentWeather(string $city = 'Tashkent')
    {
        $response = Http::get(self::BASE_URL . 'current.json', [
            'key' => self::API_KEY,
            'q' => $city,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => $response->body()
        ];
    }

    /**
     * Get weather forecast for given city and number of days (1 to 10).
     */
    public function getForecast(string $city = 'Tashkent', int $days = 5)
    {
        $response = Http::get(self::BASE_URL . 'forecast.json', [
            'key' => self::API_KEY,
            'q' => $city,
            'days' => $days,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => $response->body()
        ];
    }

    /**
     * Search for matching cities by partial name.
     */
    public function searchCity(string $query)
    {
        $response = Http::get(self::BASE_URL . 'search.json', [
            'key' => self::API_KEY,
            'q' => $query,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => $response->body()
        ];
    }
}
