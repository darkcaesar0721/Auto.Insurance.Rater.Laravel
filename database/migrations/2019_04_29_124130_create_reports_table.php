<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Report;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->boolean('effective_date')->default(false);
            $table->boolean('expiration_date')->default(false);
            $table->boolean('broker_fee')->default(false);
            $table->boolean('by_company')->default(false);
            $table->boolean('company_premium')->default(false);
            $table->boolean('down_payment')->default(false);
            $table->boolean('referral_fee')->default(false);

            $table->timestamps();
        });

        Report::create([
            'date_from' => date('Y-m-d', strtotime('-1 month', time())),
            'date_to' => date('Y-m-d')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
