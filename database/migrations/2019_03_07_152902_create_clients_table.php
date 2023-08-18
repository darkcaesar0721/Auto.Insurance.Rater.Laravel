<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_type_id')->nullable();
            $table->integer('policy_type_id')->nullable();
            $table->string('business_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('current_address_line_1')->nullable();
            $table->string('current_address_line_2')->nullable();
            $table->string('current_address_zip_code')->nullable();
            $table->string('current_address_address_city')->nullable();
            $table->string('current_address_address_state_id')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('email_address')->nullable();
            $table->boolean('no_email')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('cell_phone_2')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('fax_number')->nullable();
            $table->integer('preferred_contact_method_id')->nullable();
            $table->boolean('mailing_address')->nullable();
            $table->string('mailing_address_line_1')->nullable();
            $table->string('mailing_address_line_2')->nullable();
            $table->string('mailing_address_zip_code')->nullable();
            $table->string('mailing_address_city')->nullable();
            $table->string('mailing_address_state_id')->nullable();
            $table->string('additional_insured_first_name')->nullable();
            $table->string('additional_insured_middle_name')->nullable();
            $table->string('additional_insured_last_name')->nullable();
            $table->string('additional_insured_suffix')->nullable();
            $table->boolean('additional_insured_co_applicant')->nullable();
            $table->string('client_number')->nullable();
            $table->string('source')->nullable();
            $table->string('agent')->nullable();
            $table->string('language_spoken')->nullable();
            $table->string('created_date')->nullable();
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
            $table->string('attachment_file_1')->nullable();
            $table->string('attachment_file_2')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
