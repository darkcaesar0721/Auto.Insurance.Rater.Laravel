<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('referral_company')->nullable();
            $table->string('referral_source_contact')->nullable();
            $table->string('referral_suite')->nullable();
            $table->string('referral_city')->nullable();
            $table->string('referral_state_id')->nullable();
            $table->string('referral_zip')->nullable();
            $table->string('referral_additional_1')->nullable();
            $table->string('referral_work')->nullable();
            $table->string('referral_additional_2')->nullable();
            $table->string('referral_cell')->nullable();
            $table->string('referral_additional_3')->nullable();
            $table->string('referral_fax')->nullable();
            $table->string('referral_email')->nullable();
            $table->string('referral_website')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('referral_sources');
    }
}
