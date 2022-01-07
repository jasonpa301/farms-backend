<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Reading {

    const TABLENAME = "readings";

    public static function createReading($farmId, $dateTime, $readingTypeId, $readingValue) {
        return DB::table(self::TABLENAME)->insert([
            'farm_id' => $farmId,
            'date_time' => $dateTime,
            'reading_type_id' => $readingTypeId,
            'reading_value' => $readingValue
        ]);
    }  

    public static function getReadings() {    
        return DB::table(self::TABLENAME)
        ->join('reading_types', self::TABLENAME.'.reading_type_id', '=', 'reading_types.id')
        ->join('farms', self::TABLENAME.'.farm_id', '=', 'farms.id')
        ->select(self::TABLENAME.'.*', 'reading_types.reading_type', 'farms.farm_name')
        ->orderBy('readings.date_time', 'desc')
        ->get();
    }

    public static function getReadingsByFarm($farmId) {    
        return DB::table(self::TABLENAME)
        ->join('reading_types', self::TABLENAME.'.reading_type_id', '=', 'reading_types.id')
        ->join('farms', self::TABLENAME.'.farm_id', '=', 'farms.id')
        ->select(self::TABLENAME.'.*', 'reading_types.reading_type', 'farms.farm_name')
        ->where('farm_id', '=', $farmId)
        ->get();
    }
    
}
    