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

Route::post('/farm/addnew', function (Request $request) {
    $name = $request->name;
    $address = $request->address;
    $lat = $request->lat;
    $long = $request->long;
    $error = "";
    $success = false;
    if (!$name || !$address || !$lat || !$long) {
        $success = false;
        $error = "missing_parameters";
    }
    else {
        $farmId = Farm::getFarmId($name);
        if ($farmId) {
            $success = false;
            $error = "farm_name_exists";
        }
        else {
            $createFarm = Farm::createFarm($name, $address, $lat, $long);
            if ($createFarm) {
                $success = true;
            }
            else {
                $success = false;
                $error = "database_update_failed";
            }
        }
    }
    return (object) array(
        "success" => $success,
        "error_info" => $error
    );
});