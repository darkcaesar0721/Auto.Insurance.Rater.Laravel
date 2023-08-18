<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Clients;
use App\ClientCountry;
use App\ClientSex;
use App\ClientClass;
use App\ClientEyes;
use App\ClientHeight;
use App\User;
use Carbon\Carbon;

class UpdateClientCharacteristics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $clients = Clients::get();
        Schema::table('clients', function (Blueprint $table) {      // Drop columns
            if (Schema::hasColumn('clients', 'client_country')) {
                $table->dropColumn('client_country');
            }
            if (Schema::hasColumn('clients', 'client_height')) {
                $table->dropColumn('client_height');
            }
            if (Schema::hasColumn('clients', 'client_eyes')) {
                $table->dropColumn('client_eyes');
            }
            if (Schema::hasColumn('clients', 'client_class')) {
                $table->dropColumn('client_class');
            }
            if (Schema::hasColumn('clients', 'client_sex')) {
                $table->dropColumn('client_sex');
            }
            if (Schema::hasColumn('clients', 'client_date_of_birth')) {
                $table->dropColumn('client_date_of_birth');
            }
            if (Schema::hasColumn('clients', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('clients', 'language_spoken')) {
                $table->dropColumn('language_spoken');
            }
            if (Schema::hasColumn('clients', 'agent')) {
                $table->dropColumn('agent');
            }
            if (Schema::hasColumn('clients', 'created_date')) {
                $table->dropColumn('created_date');
            }
        });
        Schema::table('clients', function (Blueprint $table) {      // Add columns
            if (!Schema::hasColumn('clients', 'client_country_id')) {
                $table->integer('client_country_id')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_height_id')) {
                $table->integer('client_height_id')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_eyes_id')) {
                $table->integer('client_eyes_id')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_class_id')) {
                $table->integer('client_class_id')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_sex_id')) {
                $table->integer('client_sex_id')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_date_of_birth')) {
                $table->date('client_date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('clients', 'source')) {
                $table->enum('source', ['input', 'import'])->nullable();
            }
            if (!Schema::hasColumn('clients', 'language_spoken')) {
                $table->enum('language_spoken', ['english', 'spanish', 'other'])->nullable();
            }
            if (!Schema::hasColumn('clients', 'agent_id')) {
                $table->integer('agent_id')->nullable();
            }
        });
        foreach ($clients as $key => $c) {
            $client = Clients::where('id', $c->id)->first();
            
            if (!$client) {
                continue;
            }

            if (isset($c->client_country)) {
                $country = ClientCountry::where('country', $c->client_country)->first();
                if ($country) {
                    $client->client_country_id = $country->id;
                }
            }

            if (isset($c->client_height)) {
                $client_height = ClientHeight::where('height', $c->client_height)->first();
                if ($client_height) {
                    $client->client_height_id = $client_height->id;
                }
            }

            if (isset($c->client_eyes)) {
                $client_eyes = ClientEyes::where('eyes', $c->client_eyes)->first();
                if ($client_eyes) {
                    $client->client_eyes_id = $client_eyes->id;
                }
            }

            if (isset($c->client_class)) {
                $client_class = ClientClass::where('class', $c->client_class)->first();
                if ($client_class) {
                    $client->client_class_id = $client_class->id;
                }
            }

            if (isset($c->client_sex)) {
                $client_sex = ClientSex::where('sex', $c->client_sex)->first();
                if ($client_sex) {
                    $client->client_sex_id = $client_sex->id;
                }
            }

            if (isset($c->client_date_of_birth)) {
                $c->client_date_of_birth = str_replace('-', '/', $c->client_date_of_birth);
                
                $format = 'm/d/Y';
                $d = \DateTime::createFromFormat($format, $c->client_date_of_birth);                
                if ($d && $d->format($format) === $c->client_date_of_birth) {
                    $client->client_date_of_birth = $d->format('m-d-Y');
                }
            }

            if (isset($c->source)) {
                $client->source = $c->source;
            }

            if (isset($c->language_spoken)) {
                $client->language_spoken = $c->language_spoken;
            }

            if (isset($c->agent)) {
                $agent = User::where('name', $c->agent)->first();
                if ($agent) {
                    $client->agent_id = $agent->id;
                }
            }

            $client->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $clients = Clients::get();

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'client_country_id')) {
                $table->dropColumn('client_country_id');
            }
            if (Schema::hasColumn('clients', 'client_height_id')) {
                $table->dropColumn('client_height_id');
            }
            if (Schema::hasColumn('clients', 'client_eyes_id')) {
                $table->dropColumn('client_eyes_id');
            }
            if (Schema::hasColumn('clients', 'client_class_id')) {
                $table->dropColumn('client_class_id');
            }
            if (Schema::hasColumn('clients', 'client_sex_id')) {
                $table->dropColumn('client_sex_id');
            }
            if (Schema::hasColumn('clients', 'client_date_of_birth')) {
                $table->dropColumn('client_date_of_birth');
            }
            if (Schema::hasColumn('clients', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('clients', 'language_spoken')) {
                $table->dropColumn('language_spoken');
            }
            if (Schema::hasColumn('clients', 'agent_id')) {
                $table->dropColumn('agent_id');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'client_country')) {
                $table->string('client_country')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_height')) {
                $table->string('client_height')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_eyes')) {
                $table->string('client_eyes')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_class')) {
                $table->string('client_class')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_sex')) {
                $table->string('client_sex')->nullable();
            }
            if (!Schema::hasColumn('clients', 'client_date_of_birth')) {
                $table->string('client_date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('clients', 'source')) {
                $table->string('source')->nullable();
            }
            if (!Schema::hasColumn('clients', 'language_spoken')) {
                $table->string('language_spoken')->nullable();
            }
            if (!Schema::hasColumn('clients', 'agent')) {
                $table->string('agent')->nullable();
            }
            if (!Schema::hasColumn('clients', 'created_date')) {
                $table->string('created_date')->nullable();
            }
        });

        foreach ($clients as $key => $c) {
            $client = Clients::where('id', $c->id)->first();
            if (!$client) {
                continue;
            }
            
            if (isset($c->client_country_id)) {
                $country = ClientCountry::where('id', $c->client_country_id)->first();
                if ($country) {
                    $client->client_country = $country->country;
                }
            }

            if (isset($c->client_height_id)) {
                $client_height = ClientHeight::where('id', $c->client_height_id)->first();
                if ($client_height) {
                    $client->client_height = $client_height->height;
                }
            }

            if (isset($c->client_eyes_id)) {
                $client_eyes = ClientEyes::where('id', $c->client_eyes_id)->first();
                if ($client_eyes) {
                    $client->client_eyes = $client_eyes->eyes;
                }
            }

            if (isset($c->client_class_id)) {
                $client_class = ClientClass::where('id', $c->client_class_id)->first();
                if ($client_class) {
                    $client->client_class = $client_class->class;
                }
            }

            if (isset($c->client_sex_id)) {
                $client_sex = ClientSex::where('id', $c->client_sex_id)->first();
                if ($client_sex) {
                    $client->client_sex = $client_sex->sex;
                }
            }

            if (isset($c->client_date_of_birth)) {
                $client->client_date_of_birth = (new Carbon($c->client_date_of_birth))->format('m/d/Y');
            }

            if (isset($c->source)) {
                $client->source = $c->source;
            }

            if (isset($c->language_spoken)) {
                $client->language_spoken = $c->language_spoken;
            
            }

            if (isset($c->agent_id)) {
                $agent = User::where('id', $c->agent_id)->first();
                if ($agent) {
                    $client->agent = $agent->name;
                }
            }

            $client->update();
        }
    }
}
