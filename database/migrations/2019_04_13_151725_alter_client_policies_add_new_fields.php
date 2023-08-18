<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientPoliciesAddNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_policies', function (Blueprint $table) {
            $table->string('agency_total')->after('broker_fee')->nullable();
            $table->string('company_total')->after('agency_total')->nullable();
            $table->string('paymentm_method_option')->after('company_total')->nullable();
            $table->string('check_no')->after('paymentm_method_option')->nullable();
            $table->string('company_down_payment')->after('check_no')->nullable();
            $table->string('total_down_payment')->after('referral_fee')->nullable();
        });
        Schema::table('client_policies', function($table) {
            $table->dropColumn('total');
            $table->dropColumn('down_payment');
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
