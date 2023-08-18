<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\PaymentMethod;
use App\ClientAutoClub;
use App\ClientLicenseOnly;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('payment_methods');
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->timestamps();
        });
        
        $data = array(  'direct cash'           => 'Direct Cash', 
                        'direct credit card'    => 'Direct Credit Card',
                        'direct other'          => 'Direct Other',
                        'referral pay'          => 'Referral Pay',
                        'referral-customer pay' => 'Referral-Customer Pay'
                    );
        
        foreach ($data as $alias => $name) {
            $payment_method = new PaymentMethod();
            $payment_method -> name = $name;
            $payment_method -> alias = $alias;
            $payment_method -> save();
        }

        $clientsLicenseOnly = ClientLicenseOnly::get();
        $clientsAutoClub = ClientAutoClub::get();

        Schema::table('client_auto_club', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('client_auto_club', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
        
        Schema::table('client_auto_club', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('client_auto_club', 'payment_method_id')) {
                $table->integer('payment_method_id')->nullable();
            }
        });

        Schema::table('client_license_only', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('client_license_only', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
        
        Schema::table('client_license_only', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('client_license_only', 'payment_method_id')) {
                $table->integer('payment_method_id')->nullable();
            }
        });

        foreach ($clientsLicenseOnly as $value) {
            if (isset($value->payment_method)) {
                $method = PaymentMethod::where('alias', $value->payment_method)->first();
                $license = ClientLicenseOnly::where('id', $value->id)->first();
                if ($license && $method) {
                    $license->payment_method_id = $method->id;
                }
                $license -> update();
            }
        }

        foreach ($clientsAutoClub as $value) {
            if (isset($value->payment_method)) {
                $method = PaymentMethod::where('alias', $value->payment_method)->first();
                $autoClub = ClientAutoClub::where('id', $value->id)->first();
                if ($autoClub && $method) {
                    $autoClub->payment_method_id = $method->id;
                }
                $autoClub -> update();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $clientsLicenseOnly = ClientLicenseOnly::get();
        $clientsAutoClub    = ClientAutoClub::get();
        $payment_methods    = PaymentMethod::get();

        Schema::table('client_auto_club', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('client_auto_club', 'payment_method_id')) {
                $table->dropColumn('payment_method_id');
            }
        });
        Schema::table('client_license_only', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('client_license_only', 'payment_method_id')) {
                $table->dropColumn('payment_method_id');
            }
        });

        Schema::table('client_auto_club', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('client_auto_club', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });
        Schema::table('client_license_only', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('client_license_only', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });

        foreach ($clientsLicenseOnly as $value) {
            $license = ClientLicenseOnly::where('id', $value->id)->first();
            if ($license && $value->payment_method_id) {
                $paymentMethod = PaymentMethod::where('id', $value->payment_method_id)->first();
                if ($paymentMethod) {
                    $license->payment_method = $paymentMethod->alias;
                }
                $license -> update();
            }
        }

        foreach ($clientsAutoClub as $value) {
            $autoClub = ClientAutoClub::where('id', $value->id)->first();
            if ($autoClub && $value->payment_method_id) {
                $paymentMethod = PaymentMethod::where('id', $value->payment_method_id)->first();
                if ($paymentMethod) {
                    $autoClub->payment_method = $paymentMethod->alias;
                }
            }
            $autoClub->update(); 
        }

        Schema::dropIfExists('payment_methods');
    }
}
