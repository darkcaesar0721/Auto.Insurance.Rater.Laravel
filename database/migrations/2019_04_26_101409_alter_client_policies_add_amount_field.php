<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientPoliciesAddAmountField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_policies', function (Blueprint $table) {
            $table->string('amount')->after('referral_fee_option')->nullable();
        });
        Schema::table('client_policies', function($table) {
            $table->dropColumn('referral_fee');
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
