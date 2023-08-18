<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ClientCountry;
use App\Clients;

class IncreaseUserCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_countries', function (Blueprint $table) {
            if (!Schema::hasColumn('client_countries', 'alpha2')) {
                $table->string('alpha2')->nullable();
            }
            if (!Schema::hasColumn('client_countries', 'alpha2')) {
                $table->string('alpha3')->nullable();
            }
        });

        $alpha3Countries = [];

        if (file_exists(storage_path() . "/countries.json")) {
            $countries1 = ClientCountry::get();
            $countries2 = json_decode(file_get_contents(storage_path() . "/countries.json"))->countryList->country;

            $formatCountries = [];
            foreach ($countries2 as $value) {
                $formatCountries[$value->english]['alpha2'] = $value->alpha2;
                $formatCountries[$value->english]['alpha3'] = $value->alpha3;
            }
            $alpha3Countries = $formatCountries;
            foreach ($countries1 as $key => $value) {
                if (array_key_exists($value['country'], $formatCountries)) {
                    $countries1[$key]['alpha2'] = $formatCountries[$value['country']]['alpha2'];
                    $countries1[$key]['alpha3'] = $formatCountries[$value['country']]['alpha3'];
                    $countries1[$key]->update();
                } elseif (Schema::hasColumn('clients', 'client_country_id')) {
                    if (!Clients::where('client_country_id', $value->id)
                        ->orWhere('nationality_id', $value->id)
                        ->first()) {
                        $value->delete();
                    }
                }
            }
        }

        if (file_exists(storage_path() . "/countries_4lac.json")) {
            $countries1 = ClientCountry::get();
            $countries2 = json_decode(file_get_contents(storage_path() . "/countries_4lac.json"));
            foreach ($countries2 as $key => $value) {
                $countries2[$key]->text = str_replace('}', '', $value->text);
            }

            $formatCountries = [];
            foreach ($countries2 as $value) {
                $formatCountries[$value->text]['alpha2'] = $value->id;
            }

            foreach ($countries1 as $key => $value) {
                if (array_key_exists($value['country'], $formatCountries)) {
                    $countries1[$key]['alpha2'] = $formatCountries[$value['country']]['alpha2'];
                    $countries1[$key]->update();
                } elseif (Schema::hasColumn('clients', 'client_country_id')) {
                    if (!Clients::where('client_country_id', $value->id)
                        ->orWhere('nationality_id', $value->id)
                        ->first()) {
                        $value->delete();
                    }
                }
            }


            foreach ($countries2 as $key => $value) {
                $country = ClientCountry::where('country', $value->text)->first();
                if (!$country) {
                    $country = new ClientCountry();
                    $country->country = $value->text;
                    $country->alpha2 = $value->id;
                    if (array_key_exists($value->text, $alpha3Countries)) {
                        $country->alpha3 = $alpha3Countries[$value->text]['alpha3'];
                    }
                    $country->save();
                }
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
        //
    }
}
