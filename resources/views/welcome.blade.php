<?php
use Illuminate\Support\Facades\DB;

try {
    DB::connection()->getPdo();
    echo "Database connection is working";
} catch (\Exception $e) {
    die("Could not connect to the database.  Please check your configuration. error:" . $e );
}
?>
