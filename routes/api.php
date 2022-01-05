<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\CSVImport;
use App\Models\Farm;
use App\Models\ReadingType;
use App\Models\Reading;

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

Route::get('/readingtypes/all', function (Request $request) {
    return ReadingType::getReadingTypes();
});