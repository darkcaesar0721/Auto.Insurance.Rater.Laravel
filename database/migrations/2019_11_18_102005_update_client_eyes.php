<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ClientEyes;

class UpdateClientEyes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_eyes', function (Blueprint $table) {
            if (!Schema::hasColumn('client_eyes', 'alpha3')) {
                $table->string('alpha3')->nullable();
            }
        });

        Schema::table('wp_import_users', function (Blueprint $table) {
            if (Schema::hasColumn('wp_import_users', 'eyes')) {
                $table->dropColumn('eyes');
            }
        });

        $eyes = ClientEyes::get();

        foreach ($eyes as $e) {
            if (isset($e->alpha3)) {
                continue;
            }
            switch ($e->eyes) {
                case 'BROWN':
                    $e->alpha3 = 'BRN';
                    break;
                case 'GREEN':
                    $e->alpha3 = 'GRN';
                    break;
                case 'BLUE':
                    $e->alpha3 = 'BLU';
                    break;
                case 'HAZEL':
                    $e->alpha3 = 'HAZ';
                    break;
                case 'GRAY':
                    $e->alpha3 = 'GRY';
                    break;
                case 'BLACK':
                    $e->alpha3 = 'BLK';
                    break;        
                case 'OTHER':
                    $e->alpha3 = 'OTR';
                    break;
            }

            $e->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_eyes', function (Blueprint $table) {
            if (Schema::hasColumn('client_eyes', 'alpha3')) {
                $table->dropColumn('alpha3');
            }
        });

        Schema::table('wp_import_users', function (Blueprint $table) {
            if (!Schema::hasColumn('wp_import_users', 'eyes')) {
                $table->string('eyes')->nullable();
            }
        });
    }
}
