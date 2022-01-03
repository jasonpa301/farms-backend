<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/farms/all', function (Request $request) {
    return Farm::getFarms();
});

Route::get('/readings/all', function (Request $request) {
    return Reading::getReadings();
});

Route::get('/readings/farm/{farmid}', function ($farmId) {
    return Reading::getReadingsByFarm($farmId);
});