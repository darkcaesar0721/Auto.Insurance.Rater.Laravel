<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\PolicyTypes;

class CreateClientLicenseOnlyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PolicyTypes::create([
            'name' => 'License'
        ]);

        Schema::create('client_license_only', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->string('license_number');
            $table->string('payment_method');
            $table->enum('term', ['year', '3_years', '5_years', '10_years']);
            $table->string('effective_date');
            $table->string('expiration_date');
            $table->float('price');
            $table->float('ship_fee');
            $table->float('total_cost');
            $table->float('referral_amount');
            $table->float('monthly_payment');
            $table->float('company_total');
            $table->integer('referral_source_id')->nullable();
            $table->integer('check_no')->nullable();
            $table->string('photo')->nullable();
            $table->string('sign')->nullable();
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
        Schema::dropIfExists('client_license_only');
    }
}
