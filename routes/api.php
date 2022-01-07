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

Route::post('/reading/addnew', function (Request $request) {
    $farmId = $request->farmId;
    $readingTypeId = $request->readingTypeId;
    $readingValue = $request->readingValue;
    $readingTime = $request->readingTime;
    $readingType = $request->readingType;
    $error = "";
    $success = false;
    if (!$farmId || !$readingTypeId || !isset($readingValue) || !$readingTime || !$readingType) {
        $success = false;
        $error = "missing_parameters";
    }
    else {
        if ($readingType == "temperature") {
            if ($readingValue  < -50 || $readingValue  > 100) {
                $success = false;
                $error = "invalid_value";
            }
        }
        elseif ($readingType == "pH") {
            if ($readingValue  < 0 || $readingValue  > 14) {
                $success = false;
                $error = "invalid_value";
            }
        }
        elseif ($readingType == "rainFall") {
            if ($readingValue  < 0 || $readingValue  > 500) {
                $success = false;
                $error = "invalid_value";
            }
        }

        if ($error != "invalid_value") {
            $createReading = Reading::createReading($farmId, $readingTime, $readingTypeId, $readingValue);
            if ($createReading) {
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