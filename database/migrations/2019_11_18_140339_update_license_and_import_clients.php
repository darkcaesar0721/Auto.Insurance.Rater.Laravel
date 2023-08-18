<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ClientLicenseOnly;
use App\WpImportUser;
use Carbon\Carbon;

class UpdateLicenseAndImportClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $licenses = ClientLicenseOnly::get();
        $wpUsers = WpImportUser::get();

        Schema::table('client_license_only', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('client_license_only', 'effective_date')) {
                $table->dropColumn('effective_date');
            }
            if (Schema::hasColumn('client_license_only', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }
        });
        Schema::table('client_license_only', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('client_license_only', 'effective_date')) {
                $table->date('effective_date')->nullable();
            }
            if (!Schema::hasColumn('client_license_only', 'expiration_date')) {
                $table->date('expiration_date')->nullable();
            }
        });

        Schema::table('wp_import_users', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('wp_import_users', 'issued_on')) {
                $table->dropColumn('issued_on');
            }
            if (Schema::hasColumn('wp_import_users', 'birth')) {
                $table->dropColumn('birth');
            }
            if (Schema::hasColumn('wp_import_users', 'order_date')) {
                $table->dropColumn('order_date');
            }
        });
        Schema::table('wp_import_users', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('wp_import_users', 'issued_on')) {
                $table->date('issued_on')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'birth')) {
                $table->date('birth')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'order_date')) {
                $table->datetime('order_date')->nullable();
            }
        });

        foreach ($licenses as $licese) {
            if (isset($licese->effective_date)) {
                $format = 'm/d/Y';
                $d = \DateTime::createFromFormat($format, $licese->effective_date);                
                if ($d && $d->format($format) === $licese->effective_date) {
                    $licese->effective_date = $d->format('Y-m-d');
                }
                else {
                    $format = 'Y/m/d';
                    $d = \DateTime::createFromFormat($format, $licese->effective_date);                
                    if (!$d || $d->format($format) !== $licese->effective_date) {
                        dd("Wrong format: $format; expected: $licese->effective_date"); 
                    }
                }
            }

            if (isset($licese->expiration_date)) {
                $licese->expiration_date = str_replace('-', '/', $licese->expiration_date);
                $format = 'm/d/Y';
                $d = \DateTime::createFromFormat($format, $licese->expiration_date);                
                if ($d && $d->format($format) === $licese->expiration_date) {
                    $licese->expiration_date = $d->format('Y-m-d');
                } 
                else {
                    $format = 'Y/m/d';
                    $d = \DateTime::createFromFormat($format, $licese->expiration_date);                
                    if (!$d || $d->format($format) !== $licese->expiration_date) {
                        dd("Wrong format: $format; expected: $licese->expiration_date"); 
                    }
                }
            }
            $licese->update();
        }

        foreach ($wpUsers as $value) {
            $user = WpImportUser::where('id', $value->id)->first();
            if ($user) {
                if (isset($value->birth)) {
                    $user->birth = $value->birth;
                }
                if (isset($value->issued_on)) {
                    $user->issued_on = $value->issued_on;
                }
                if (isset($value->order_date)) {
                    $user->order_date = (new \DateTime($value->order_date))->format('Y-m-d H:i:s');
                }
                $user->update();
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
        $licenses = ClientLicenseOnly::get();
        $wpUsers = WpImportUser::get();

        Schema::table('client_license_only', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('client_license_only', 'effective_date')) {
                $table->dropColumn('effective_date');
            }
            if (Schema::hasColumn('client_license_only', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }
        });
        Schema::table('client_license_only', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('client_license_only', 'effective_date')) {
                $table->string('effective_date')->nullable();
            }
            if (!Schema::hasColumn('client_license_only', 'expiration_date')) {
                $table->string('expiration_date')->nullable();
            }
        });

        Schema::table('wp_import_users', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('wp_import_users', 'birth')) {
                $table->dropColumn('birth');
            }
            if (Schema::hasColumn('wp_import_users', 'issued_on')) {
                $table->dropColumn('issued_on');
            }
            if (Schema::hasColumn('wp_import_users', 'order_date')) {
                $table->dropColumn('order_date');
            }
        });
        Schema::table('wp_import_users', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('wp_import_users', 'birth')) {
                $table->string('birth')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'issued_on')) {
                $table->string('issued_on')->nullable();
            }
            if (!Schema::hasColumn('wp_import_users', 'order_date')) {
                $table->string('order_date')->nullable();
            }
        });

        foreach ($licenses as $l) {
            $licese = ClientLicenseOnly::where('id', $l->id)->first();
            
            if (!$licese) {
                continue;
            }
            if (isset($l->effective_date)) {
                $licese->effective_date = (new Carbon($l->effective_date))->format('m/d/Y');
            }
            if (isset($l->expiration_date)) {
                $licese->expiration_date = (new Carbon($l->expiration_date))->format('m/d/Y');
            }
            $licese->update();
        }

        foreach ($wpUsers as $value) {
            $user = WpImportUser::where('id', $value->id)->first();
            if ($user) {
                if (isset($value->birth)) {
                    $format = 'Y-m-d';
                    $d = \DateTime::createFromFormat($format, $value->birth);
                    if ($d && $d->format($format) === $value->birth) {
                        $user->birth = $d->format('m-d-Y'); 
                    }
                }
                if (isset($value->issued_on)) {
                    $format = 'Y-m-d';
                    $d = \DateTime::createFromFormat($format, $value->issued_on);
                    if ($d && $d->format($format) === $value->issued_on) {
                        $user->issued_on = $d->format('m-d-Y'); 
                    }
                }
                if (isset($value->order_date)) {
                    $format = 'Y-m-d H:i:s';
                    $d = \DateTime::createFromFormat($format, $value->order_date);
                    if ($d && $d->format($format) === $value->order_date) {
                        $user->order_date = $d->format('m/d/Y H:i:s'); 
                    }
                }
                $user->update();
            }

        }
    }
}
