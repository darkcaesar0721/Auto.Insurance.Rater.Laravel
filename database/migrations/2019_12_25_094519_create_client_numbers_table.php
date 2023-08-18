<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Clients;
use App\ClientNumber;

class CreateClientNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $clients = Clients::get();
        Schema::dropIfExists('client_numbers');

        if (!Schema::hasTable('client_numbers')) {
            Schema::create('client_numbers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('client_id');
                $table->string('number')->nullable()->unique();
                $table->boolean('added_manually')->default(true);
                $table->index('client_id');
                $table->softDeletes();
                $table->timestamps();
            });    
        }

        foreach ($clients as $key => $value) {
            $client_no = new ClientNumber();
            $client_no->number = $value->client_number;
            $client_no->client_id = $value->id;
            $client_no->save();
        }

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'client_number')) {
                $table->dropColumn('client_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $client_numbers = ClientNumber::get();
        
        Schema::dropIfExists('client_numbers');

        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'client_number')) {
                $table->string('client_number')->nullable()->unique();
            }
        });

        foreach ($client_numbers as $key => $value) {
            $client = Clients::where('id', $value->client_id)->first();
            if ($client) {
                $client->client_number = $value->number;
                $client->save();
            }
        }
    }
}
