<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('signature');
            $table->string('ip');
            $table->text('signing');
            $table->text('countryName');
            $table->string('countryCode');
            $table->string('regionCode');
            $table->string('regionName');
            $table->string('cityName');
            $table->string('latitude');
            $table->string('longitude');
            $table->text('perihal');
            $table->text('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signatures');
    }
}
