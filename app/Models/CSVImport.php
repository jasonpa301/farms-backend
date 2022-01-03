<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class CSVImport {

    public static function getCSVContents($fileName, $delimiter = ',') {
        $filePath = public_path()."/uploads/".$fileName;
        $file = fopen($filePath, 'r');
        
        $rows = array();
        while (($line = fgetcsv($file)) !== FALSE) {
            $rows[] = $line;
        }
        fclose($file);
        $farmName = false;
        $farmId = false;
        if ($fileName == "friman_metsola.csv") {
            $farmName = "Friman Metsola collective";
        }
        if ($fileName == "Nooras_farm.csv") {
            $farmName = "Noora's farm";
        }
        if ($fileName == "ossi_farm.csv") {
            $farmName = "Organic Ossi's Impact That Lasts plantase";
        }  
        if ($fileName == "PartialTech.csv") {
            $farmName = "PartialTech Research Farm";
        }

        if ($farmName) {
            $farmId = Farm::getFarmId($farmName);
        }

        $readingTypes = ReadingType::getReadingTypes();
        
        $rowCount = 0;
        foreach ($rows as $row) {
            if ($rowCount == 0) {
                $rowCount++; 
                continue;
            }
            $farm = $row[0];
            $dateTime = $row[1];
            $readingType =$row[2];
            $value = $row[3];
            if ($farmId) {
                $rowFarmId = $farmId;
            }
            else {
                continue;
            }
            $readingTypeId = false;
            foreach($readingTypes as $readingTypeRow) {
                if ($readingType == $readingTypeRow->reading_type) {
                    $readingTypeId = $readingTypeRow->id; 
                    break; 
                }
            }
            if (!$readingTypeId) {
                continue;
            }

            if (!$value || is_null($value) || $value == "null" || $value == "NULL") {
                continue;
            }
            if (!is_numeric($value)) {
                continue;
            }
            if ($readingType == "temperature") {
                if ($value < -50 || $value > 100) {
                    continue;
                }
            }
            if ($readingType == "pH") {
                if ($value < 0 || $value > 14) {
                    continue;
                }
            }
            if ($readingType == "rainFall") {
                if ($value < 0 || $value > 500) {
                    continue;
                }
            }

            $timeRaw = strtotime($dateTime);
            $dateTimeFormat = date('Y-m-d H:i:s', $timeRaw);
            

            Reading::createReading($rowFarmId, $dateTimeFormat, $readingTypeId, $value);
            $rowCount++; 

        }
        
    }    
}
    