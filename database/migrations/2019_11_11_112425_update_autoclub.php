<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ClientAutoClub;
use Carbon\Carbon;

class UpdateAutoclub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $clients = ClientAutoClub::get();
        
        Schema::table('client_auto_club', function (Blueprint $table) {
            if (Schema::hasColumn('client_auto_club', 'effective_date')) {
                $table->dropColumn('effective_date');
            }

            if (Schema::hasColumn('client_auto_club', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }
        });

        Schema::table('client_auto_club', function (Blueprint $table) {
            $table->date('effective_date')->nullable();
            $table->date('expiration_date')->nullable();
        });

        foreach ($clients as $key => $value) {
            $changeClient = ClientAutoClub::where('id', $value->id)->first();
            
            if ($changeClient && $value->effective_date && $value->expiration_date) {
                $changeClient->effective_date = new \DateTime($value->effective_date);
                $changeClient->expiration_date = new \DateTime($value->expiration_date);
                $changeClient->update();
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
        $clients = ClientAutoClub::get();
        
        Schema::table('client_auto_club', function (Blueprint $table) {
            if (Schema::hasColumn('client_auto_club', 'effective_date')) {
                $table->dropColumn('effective_date');
            }

            if (Schema::hasColumn('client_auto_club', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }
        });

        Schema::table('client_auto_club', function (Blueprint $table) {
            $table->string('effective_date')->nullable();
            $table->string('expiration_date')->nullable();
        });

        foreach ($clients as $key => $value) {
            $changeClient = ClientAutoClub::where('id', $value->id)->first();
            
            if ($changeClient && $value->effective_date && $value->expiration_date) {
                $changeClient->effective_date = (new Carbon($value->effective_date))->format('m/d/Y');
                $changeClient->expiration_date = (new Carbon($value->expiration_date))->format('m/d/Y');
                $changeClient->update();
            }
        }
    }
}
