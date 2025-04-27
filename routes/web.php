<?php

use Illuminate\Support\Facades\Route;


Route::any('/docs/{any}', function ($any) {
    return redirect('/api/documentation');
})->where('any', '.*');

Route::get('/', function () {
    return view('welcome');
});
