<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->dateTime('date_time');
            $table->unsignedBigInteger('reading_type_id');
            $table->decimal('reading_value');
        });

        Schema::table('readings', function($table) {
            $table->foreign('farm_id')->references('id')->on('farms');
            $table->foreign('reading_type_id')->references('id')->on('reading_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('readings');
    }
}
