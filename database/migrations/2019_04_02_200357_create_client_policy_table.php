<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Clients;
use App\ClientPolicy;

class CreateClientPolicyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_policies', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->nullable();
            $table->integer('company_list_id')->nullable();
            $table->date('effective_date');
            $table->enum('term', ['year', '6_months', '3_months', 'monthly']);
            $table->date('expiration_date');
            $table->string('policy_number');
            $table->float('premium');
            $table->float('co_fees');
            $table->float('broker_fee');
            $table->float('total');
            $table->float('down_payment');
            $table->float('monthly_payment');
            $table->enum('referral_fee_option', ['yes', 'no']);
            $table->float('referral_fee')->nullable();

            $table->timestamps();
        });

        $allClients = Clients::all();
        foreach ($allClients as $client) {
            ClientPolicy::create([
                'client_id' => $client->id,
                'company_list_id' => $client->company_id,
                'effective_date' => date('Y-m-d', strtotime($client->effective_date)),
                'term' => $client->term,
                'expiration_date' => date('Y-m-d', strtotime($client->expiration_date)),
                'policy_number' => $client->policy_number,
                'premium' => $client->premium,
                'co_fees' => $client->co_fees,
                'broker_fee' => $client->broker_fee,
                'total' => $client->total,
                'down_payment' => $client->down_payment,
                'monthly_payment' => $client->monthly_payment,
                'referral_fee_option' => $client->referral_fee_options,
                'referral_fee' => $client->referral_fee
            ]);
        }

        Schema::table('clients', function($table) {
            $table->dropColumn('company_id');
            $table->dropColumn('effective_date');
            $table->dropColumn('term');
            $table->dropColumn('expiration_date');
            $table->dropColumn('policy_number');
            $table->dropColumn('premium');
            $table->dropColumn('co_fees');
            $table->dropColumn('broker_fee');
            $table->dropColumn('total');
            $table->dropColumn('down_payment');
            $table->dropColumn('monthly_payment');
            $table->dropColumn('referral_fee_options');
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
        Schema::table('clients', function($table) {
            $table->string('company_id')->nullable();
            $table->string('effective_date')->nullable();
            $table->string('term')->nullable();
            $table->string('expiration_date')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('premium')->nullable();
            $table->string('co_fees')->nullable();
            $table->string('broker_fee')->nullable();
            $table->string('total')->nullable();
            $table->string('down_payment')->nullable();
            $table->string('monthly_payment')->nullable();
            $table->string('referral_fee_options')->nullable();
            $table->string('referral_fee')->nullable();
        });
        Schema::dropIfExists('client_policies');
    }
}
