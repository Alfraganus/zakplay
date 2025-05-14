<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\TabletController;
use App\Http\Controllers\Api\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;


Route::middleware('auth:sanctum')->post('/getme', [AuthController::class, 'getMe']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tablets', TabletController::class);
});
Route::apiResource('drivers', DriverController::class);

Route::apiResource('leaderboards',  LeaderboardController::class);
Route::apiResource('car-models', CarsController::class);
Route::get('car-colors', [CarsController::class, 'getCarColors']);
Route::post('/drivers/by-pin', [DriverController::class, 'getByPincode']);
Route::get('/leaderboards/{id}/results', [LeaderboardController::class, 'getResults']);

Route::get('/dashboard/user-counts', [DashboardController::class, 'getCounts']);
Route::get('/dashboard/weekly-test-stats', [DashboardController::class, 'getWeeklyTestStats']);


Route::get('/weather/current', [WeatherController::class, 'currentWeather']);
Route::get('/weather/forecast', [WeatherController::class, 'forecast']);
Route::get('/weather/search', [WeatherController::class, 'search']);


require base_path('app/Modules/test/Routes.php');
