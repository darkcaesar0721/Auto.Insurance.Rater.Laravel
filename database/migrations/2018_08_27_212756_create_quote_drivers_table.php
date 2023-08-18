<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quote_id');

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('dob')->nullable();
            $table->boolean('good_driver')->nullable();
            $table->integer('good_student_age')->nullable();
            $table->string('license_no')->nullable();
            $table->string('license_status')->nullable();
            $table->string('licensing_status')->nullable();
            $table->boolean('sr_22')->nullable();
            $table->string('state')->nullable();

            $table->string('marital_status')->nullable();
            $table->boolean('spouse_is_driver')->default(false);
            $table->string('wife_dob')->nullable();
            $table->string('wife_first_name')->nullable();
            $table->string('wife_license_status')->nullable();
            $table->string('wife_licensing_status')->nullable();
            $table->boolean('wife_sr_22')->nullable();

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
        Schema::dropIfExists('quote_drivers');
    }
}
