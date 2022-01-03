<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Farm {

    const TABLENAME = "farms";

    public static function createFarm($farmName, $address, $lat, $long) {
        DB::table(self::TABLENAME)->insert([
            'farm_name' => $farmName,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $long
        ]);
    }
    
    public static function createDefaults() {
        $farms = array(
            (object) array(
                "farm" => "Friman Metsola collective",
                "address" => "Rauhankatu 19, 15110 Lahti",
                "lat" => 60.985040,
                "long" => 25.654610
                
            ),
            (object) array(
                "farm" => "Noora's farm",
                "address" => "Ajokatu 53, 15500 Lahti",
                "lat" => 60.964991, 25.657180,
                "long" => 25.657180
                
            ),
            (object) array(
                "farm" => "Organic Ossi's Impact That Lasts plantase",
                "address" => "Askonkatu 9, 15100 Lahti",
                "lat" => 60.977008,
                "long" => 25.669474
                
            ),
            (object) array(
                "farm" => "PartialTech Research Farm",
                "address" => "Eteläesplanadi 8, 00130 Helsinki",
                "lat" => 60.167170,
                "long" =>  24.949153
                
            )
        );


        foreach($farms as $farm) {
            self::createFarm($farm->farm, $farm->address, $farm->lat, $farm->long);
        }

    }

}
    

