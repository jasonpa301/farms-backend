<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public static function getMonthlyAverages($farmId, $readingTypeId) {    
        $data = DB::table(self::TABLENAME)
            ->select('reading_value', 'date_time')
            ->where('farm_id', '=', $farmId)
            ->where('reading_type_id', '=', $readingTypeId)
            ->orderBy('date_time', 'asc')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->date_time)->format('Ym');
            });
            
        $returnArray = array();
        $index = 0;
        $currentYear = 0;
        $currentMonthIndex = 1;
        foreach($data as $monthArray) {
            $datetime = $monthArray[0]->date_time;
            $year = Carbon::parse($datetime)->format('Y');
            $month = Carbon::parse($datetime)->format('m');
            $newYear = false;
            if (!$currentYear) {
                $newYear = true;
                $currentYear = $year;
                $currentMonthIndex = 1;
                $returnArray[$index] = (object) array(
                        "year" => $currentYear,
                        "data" => array()
                );
            }
            if ($currentYear && $currentYear != $year) {
                $currentYear = $year;
                $newYear = true;
                $currentMonthIndex = 1;
                $index++;
                $returnArray[$index] = (object) array(
                    "year" => $currentYear,
                    "data" => array()
                );
            }
            $readingValueSum = 0;
            $readingValueCount = 0;
            $readingValueAverage = null;
           
            foreach($monthArray as $monthData) {
                $readingValueSum += $monthData->reading_value;
                $readingValueCount++;
            }
            $readingValueAverage =  $readingValueSum / $readingValueCount;
        
            
            if ($newYear && $month != "01") {
                $currentMonthIndex = (int)$month;
                $elements = $currentMonthIndex - 1;
                $returnArray[$index]->data = array_fill(0, $elements, null );
                
            }
            if ($currentMonthIndex != (int)$month) {
                $elements = (int)$month - $currentMonthIndex;
                for($i = 0; $i < $elements; $i++) {
                    $returnArray[$index]->data[] = null;
                } 
            }
            $returnArray[$index]->data[] = round($readingValueAverage, 2);
            $currentMonthIndex++;
        }

        return $returnArray;
            
    }
    
}
    