<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_license_only', function($table){
            $table->dropColumn('referral_amount');
            $table->dropColumn('monthly_payment');
            $table->dropColumn('check_no');
            $table->dropColumn('company_total');
            $table->string('tracking_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
