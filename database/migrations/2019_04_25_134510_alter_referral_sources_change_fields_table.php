<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReferralSourcesChangeFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_sources', function (Blueprint $table) {
            $table->string('referral_contact_name')->after('referral_company')->nullable();
            $table->string('referral_address')->after('referral_contact_name')->nullable();
        });
        Schema::table('referral_sources', function($table) {
            $table->dropColumn('referral_source_contact');
            $table->dropColumn('referral_suite');
            $table->dropColumn('referral_additional_1');
            $table->dropColumn('referral_additional_2');
            $table->dropColumn('referral_additional_3');
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
