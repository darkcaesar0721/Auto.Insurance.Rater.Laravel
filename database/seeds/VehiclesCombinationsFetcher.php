<?php

use App\Http\Controllers\Api\AutoQuoteController;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class VehiclesCombinationsFetcher extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dd("Don't run it - use vehiclesCombination-19.sql");

        ini_set('max_execution_time', -1);
        $dusk = new \duncan3dc\Laravel\Dusk();

        /*
        |--------------------------------------------------------------------------
        | First Page
        |--------------------------------------------------------------------------
        */
        $dusk->visit("https://secure.consumerratequotes.com/consumer/QuoteStart.aspx?id=56195&StartPage=Default");

        $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_cbLine_Arrow', "Personal Auto"));
        $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tbZipcode', '90004', 'ctl00_ContentPlaceHolder1_tbZipcode_ClientState'));
        $dusk->click('input[type="submit"]');

        /*
        |--------------------------------------------------------------------------
        | Drivers Page
        |--------------------------------------------------------------------------
        */
        $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_NameInput1_tbFirst', 'John', 'ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_NameInput1_tbFirst_ClientState'));
        $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_NameInput1_tbLast', 'Doe', 'ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_NameInput1_tbLast_ClientState'));
        $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_tbAddress', '1916 Colorado Blvd Ste C', 'ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_tbAddress_ClientState'));
        $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Email1', 'test@example.com', 'ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Email1_ClientState'));
        $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Phone1', '(123)123-1231', 'ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Phone1_ClientState'));

        $dob = Carbon\Carbon::parse('12/10/1990')->format('Y-m-d-H-i-s');
        $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_DOB1', $dob, 'ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_DOB1_ClientState'));

        $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Gender1_Arrow', "Male"));
        $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_MaritalStatus1_Arrow', "Single"));
        $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Driver_Occupation1_cbIndustry_Arrow', "Chartered Accountant"));
        $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Driver_Occupation1_cbOccupation_Arrow', "Other"));
        $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_IncidentInput1_cbIncident_Arrow', "No"));

        $dusk->click('input[type="submit"][title="Continue to Vehicle Information"]');

        /*
        |--------------------------------------------------------------------------
        | Vehicles Page
        |--------------------------------------------------------------------------
        */
        $yearsArr = $dusk->script("
            document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbYear_Arrow').click();
            
            var values = [];
    
            $('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbYear_DropDown ul.rcbList').children().each(function(i, el) {
                if (!el.innerText) { return; } 
                values.push(el.innerText);
            })
            
            return values;
        ")[0];

        $yearsCol = collect($yearsArr)->map(function ($value, $key) {
            return collect([
                'name' => $value
            ]);
        });


        \App\Year::insert($yearsCol->toArray());
        $years = \App\Year::get();

        foreach ($years as $year) {
            $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbYear_Arrow', $year->name));

            $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_Arrow').click();");

            $dusk->waitUntil('!$.active' , 20);
            $dusk->waitUntil('$("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_DropDown ul.rcbList").children().length > 0 &&
                        $("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_DropDown ul.rcbList > .rcbLoading").length == 0', 10);

            $makesArr = $dusk->script("var values = [];
        
                $('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_DropDown ul.rcbList').children().each(function(i, el) {
                    if (!el.innerText || el.innerText === 'Loading...') { return; } 
                    values.push(el.innerText);
                })
                
                return values;
            ")[0];

            $makesData = collect($makesArr)->map(function($val, $key) use ($year) {
                return collect([
                    'year_id' => $year->id,
                    'name' => $val
                ]);
            });

            \App\Make::insert($makesData->toArray());
            $makes = \App\Make::where('year_id', $year->id)->get();

            foreach ($makes as $make) {
                $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_Arrow', $make->name));

                $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_Arrow').click();");

                $dusk->waitUntil('!$.active' , 20);
                $dusk->waitUntil('$("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_DropDown ul.rcbList").children().length > 0 &&
                                $("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_DropDown ul.rcbList > .rcbLoading").length == 0', 10);

                    $modelsArr = $dusk->script("var models = [];
    
                    $('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_DropDown ul.rcbList').children().each(function(i, el) {
                        if (!el.innerText || el.innerText === 'Loading...') { return; }
                        models.push(el.innerText.trim());
                    })
    
                    return models;
                ")[0];

                $modelsData = collect($modelsArr)->map(function($val, $key) use ($make) {
                    return collect([
                        'make_id' => $make->id,
                        'name' => $val
                    ]);
                });

                \App\VehicleModel::insert($modelsData->toArray());
                $models = \App\VehicleModel::where('make_id', $make->id)->get();


                foreach ($models as $model) {
                    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_Arrow', $model->name));

                    $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_Arrow').click();");

                    try {
                        $dusk->waitUntil('!$.active' , 40);
                        $dusk->waitUntil('$("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_DropDown ul.rcbList").children().length > 0 &&
                            $("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_DropDown ul.rcbList > .rcbLoading").length == 0', 10);
                    } catch (Exception $e) {
                        Log::error("$year->name, $make->name, $model->name - NO SUB-MODELS AVAILABLE");
                        continue;
//                    $dusk->dump();
                    }


                    $subModelsArr = $dusk->script("var subModels = [];
    
                        $('body').find('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_DropDown ul.rcbList').children().each(function(i, el) {
                            if (!el.innerText || el.innerText === 'Loading...' || !el.innerText.trim()) { return; }
                            subModels.push(el.innerText.trim());
                        })
    
                        return subModels;
                    ")[0];

                    $subModelsData = collect($subModelsArr)->map(function($val, $key) use ($model) {
                        return collect([
                            'vehicle_model_id' => $model->id,
                            'name' => $val
                        ]);
                    });

                    \App\SubModel::insert($subModelsData->toArray());
                }
            }
        }
    }
}
