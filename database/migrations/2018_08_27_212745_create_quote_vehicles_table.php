<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id');

            $table->string('vin_no')->nullable();
            $table->string('year');
            $table->string('make');
            $table->string('model');
            $table->string('sub_model');
            $table->string('coverage');
            $table->string('usage')->nullable();
            $table->string('alarm')->nullable();

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
        Schema::dropIfExists('quote_vehicles');
    }
}
