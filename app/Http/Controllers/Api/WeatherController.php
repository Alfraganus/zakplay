<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\WeatherService;
use Illuminate\Http\Request;

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
        return response()->json($this->weather->getForecast($city, $days));
    }

    public function search(Request $request)
    {
        $query = $request->input('query', 'Tash');
        return response()->json($this->weather->searchCity($query));
    }
}
