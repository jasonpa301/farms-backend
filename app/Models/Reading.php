<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Reading {

    const TABLENAME = "readings";

    public static function createReading($farmId, $dateTime, $readingTypeId, $readingValue) {
        DB::table(self::TABLENAME)->insert([
            'farm_id' => $farmId,
            'date_time' => $dateTime,
            'reading_type_id' => $readingTypeId,
            'reading_value' => $readingValue
        ]);
    }  
    
}
    