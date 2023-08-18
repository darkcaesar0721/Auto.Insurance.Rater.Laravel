<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientPolicyTableChangeTermType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::table('client_policies', function($table) {
            $table->dropColumn('term');
        });
        Schema::table('client_policies', function (Blueprint $table) {
            $table->enum('term', ['year', '6_months', '3_months', 'monthly', 'endorsement'])->after('effective_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
