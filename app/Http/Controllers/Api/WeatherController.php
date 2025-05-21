<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    protected $weather;

    public function __construct(WeatherService $weather)
    {
        $this->weather = $weather;
    }

    public function currentWeather(Request $request)
    {
        $city = $request->input('city', 'Tashkent');
        return response()->json($this->weather->getCurrentWeather($city));
    }


    public function forecast(Request $request)
    {
        $city = $request->input('city', 'Tashkent');
        $days = $request->input('days', 5);

        $cacheKey = 'weather:' . strtolower($city) . ':' . now()->toDateString();

        $result = Cache::remember($cacheKey, now()->addDay(), function () use ($city, $days) {
            $forecast = $this->weather->getForecast($city, $days);

            $forecastData = array_map(function ($day) {
                return [
                    'date' => $day['date'],
                    'mintemp_c' => $day['day']['mintemp_c'],
                    'maxtemp_c' => $day['day']['maxtemp_c'],
                    'avgtemp_c' => $day['day']['avgtemp_c'],
                    'condition' => $day['day']['condition'],
                ];
            }, $forecast['forecast']['forecastday']);

            return [
                'location' => $forecast['location'],
                'current' => [
                    'temp_c' => $forecast['current']['temp_c'],
                    'condition' => $forecast['current']['condition'],
                    'forecast' => $forecastData,
                ],
            ];
        });

        return response()->json($result);
    }

    public function search(Request $request)
    {
        $query = $request->input('query', 'Tash');
        return response()->json($this->weather->searchCity($query));
    }
}
