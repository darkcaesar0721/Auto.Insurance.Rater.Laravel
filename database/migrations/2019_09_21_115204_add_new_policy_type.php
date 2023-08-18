<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\PolicyTypes;

class AddNewPolicyType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PolicyTypes::create([
            'name' => 'Auto Club'
        ]);

        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('auto_club');
            $table->boolean('auto_club_license_only');
        });

        Schema::create('client_auto_club', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->string('member_id');
            $table->string('payment_method');
            $table->enum('term', ['year', '6_months', '3_months', 'monthly']);
            $table->string('effective_date');
            $table->string('expiration_date');
            $table->float('premium');
            $table->float('co_fees');
            $table->float('down_payment');
            $table->float('referral_amount');
            $table->float('monthly_payment');
            $table->float('company_total');
            $table->integer('referral_source_id')->nullable();
            $table->integer('check_no')->nullable();
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
        //
    }
}
