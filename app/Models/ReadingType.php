<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReadingType {
    
    const TABLENAME = "reading_types";
    
    public static function createReadingType($readingType) {
        DB::table(self::TABLENAME)->insert([
            'reading_type' => $readingType,
        ]);
    }  
    
    public static function createDefaults() {
        $readingTypes = array(
            "temperature",
            "pH",
            "rainFall"
        );

        foreach($readingTypes as $readingType) {
            self::createReadingType($readingType);
        }
    }

}
    