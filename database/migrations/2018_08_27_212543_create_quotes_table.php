<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('zip');
            $table->string('coverage');
            $table->string('payment_type')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('agent_no')->nullable();
            $table->string('total_quoted_amount')->nullable();
            $table->string('quote_company')->nullable();
            $table->boolean('card_authorized')->default(false);
            $table->boolean('email_verified')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->string('note')->nullable();
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
        Schema::dropIfExists('quotes');
    }
}
