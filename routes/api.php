<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/debug-user', function (Request $request) {
    return dd(auth()->user());
});
Route::middleware('auth:sanctum')->post('/getme', [AuthController::class, 'getMe']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//Route::post('/getme', [AuthController::class, 'getMe']);

require base_path('app/Modules/test/Routes.php');
