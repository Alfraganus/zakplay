<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/docs', [SwaggerController::class, 'index']);
Route::get('/docs/{any}', [SwaggerController::class, 'index'])->where('any', '.*');
require base_path('app/Modules/test/Routes.php');
