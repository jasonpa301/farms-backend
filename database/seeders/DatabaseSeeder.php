<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\ReadingType;
use \App\Models\Farm;
use \App\Models\CSVImport;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Farm::createDefaults();
        ReadingType::createDefaults();
        CSVImport::getCSVContents("Nooras_farm.csv");
        CSVImport::getCSVContents("friman_metsola.csv");
        CSVImport::getCSVContents("ossi_farm.csv");
        CSVImport::getCSVContents("PartialTech.csv");
    }
}
