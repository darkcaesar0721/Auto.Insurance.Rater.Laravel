<?php

namespace App\Http\Controllers\Api;

use App\Hasher;
use App\Http\Controllers\Controller;
use App\Make;
use App\Notifications\QuoteCreated;
use App\Quote;
use App\QuoteVehicle;
use App\Services\ImageComparer;
use App\SubModel;
use App\VehicleModel;
use App\Year;
use Carbon\Carbon;
use duncan3dc\Laravel\Dusk;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Array_;

class AutoQuoteController extends Controller
{
    public function askQuote(Request $request) {
        $request->validate([
            'vehicles_details' => 'required',
            'drivers_details' => 'required',
            'zip' => 'required',
            'coverage' => 'required'
        ]);

        $driversDetails = $request->drivers_details;
        foreach ($driversDetails as $i => $driver) {
            if (isset($driver['spouse_is_driver'])) {
                $spouse = [
                    'first_name' => $driver['wife_first_name'],
                    'last_name' => $driver['wife_last_name'],
                    'dob' => $driver['wife_dob'],
                    'license_no' => $driver['wife_license_no'],
                    'gender' => $driver['gender'] == 'male' ? 'female' : 'male',
                    'marital_status' => 'Spouse'
                ];

                array_splice( $driversDetails, $i+1, 0, [$spouse] );
            }
        }

        $driversCount = count($driversDetails);
        $vehiclesCount = count($request->vehicles_details);

        $dusk = new Dusk();

        /*
        |--------------------------------------------------------------------------
        | Initial Page
        |--------------------------------------------------------------------------
        */
        $dusk->visit("https://secure.consumerratequotes.com/consumer/QuoteStart.aspx?id=56195&StartPage=Default");

        $dusk->script(self::selectDropdownJavascript('ctl00_ContentPlaceHolder1_cbLine_Arrow', "Personal Auto"));
        $dusk->script(self::parseJavascript('ctl00_ContentPlaceHolder1_tbZipcode', $request->zip, 'ctl00_ContentPlaceHolder1_tbZipcode_ClientState'));

        $dusk->click('input[type="submit"]');

        /*
        |--------------------------------------------------------------------------
        | Drivers Page
        |--------------------------------------------------------------------------
        */
        if ($dusk->script('return document.getElementById("ctl00_ContentPlaceHolder1_lbHeading").innerHTML')[0] !== 'Driver Information') {
            throw new \Exception("Failed to go Driver Page");
        }

        foreach ($driversDetails as $i => $driver) {
            $driverIndex = $i + 1;
            $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_NameInput{$driverIndex}_tbFirst", $driver['first_name'], "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_NameInput{$driverIndex}_tbFirst_ClientState"));
            $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_NameInput{$driverIndex}_tbLast", $driver['last_name'], "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_NameInput{$driverIndex}_tbLast_ClientState"));

            if ($i == 0) {
                $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_tbAddress", $request['details']['address'] ?? "1916 Colorado Blvd Ste C", "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_tbAddress_ClientState"));
                $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Email1", $request['details']['email'] ?? 'test@test.com', "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Email1_ClientState"));
                $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Phone1", '(123)456-7891', "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Phone1_ClientState"));
            }

            $dob = Carbon::parse($driver['dob'])->format('Y-m-d-H-i-s');
            $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_DOB{$driverIndex}", $dob, "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_DOB{$driverIndex}_ClientState"));

            $gender = $driver['gender'] === "female" ? "Female" : "Male";
            $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_Gender{$driverIndex}_Arrow", $gender));

            $maritalStatus = $driver['marital_status'] == 'married' ? "Married" : "Single";
            $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_MaritalStatus{$driverIndex}_Arrow", $maritalStatus));

            $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_Driver_Occupation{$driverIndex}_cbIndustry_Arrow", "Other"));
            $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_Driver_Occupation{$driverIndex}_cbOccupation_Arrow", "Other"));

            if (isset($driver['license_no'])) {
                $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_DriverLicense{$driverIndex}", $driver['license_no'], "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_DriverLicense{$driverIndex}_ClientState"));
            }

            $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_IncidentInput{$driverIndex}_cbIncident_Arrow", "No"));

            if ($driverIndex > 1) {
                $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_Relation{$driverIndex}_Arrow", "Other", "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver{$driverIndex}_Relation{$driverIndex}_DropDown"));
            }

            if ($driverIndex < $driversCount) {
                $dusk->script("$(`input[type='submit'][value='Add Additional Driver']`).click()");
                $dusk->script("$('#__tab_ctl00_ContentPlaceHolder1_tcDrivers_tpDriver" . ($driverIndex + 1) . "').click()");
            }
        }

        $dusk->script('$(`input[type="submit"][value="Continue"]`).click()');

        /*
        |--------------------------------------------------------------------------
        | Vehicles Page
        |--------------------------------------------------------------------------
        */
        if ($dusk->script('return document.getElementById("ctl00_ContentPlaceHolder1_lbHeading").innerHTML')[0] !== 'Vehicle Information') {
            $dusk->dump();
            throw new \Exception("Failed to go Vehicle Page");
        }

        foreach ($request->vehicles_details as $i => $vehicle) {
            $vehicleIndex = $i + 1;

            if (isset($vehicle['vin_no']) && !empty($vehicle['vin_no'])) {
                $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_tbVIN", $vehicle['vin_no'], "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_tbVIN_ClientState"));
            } else {
                $makeVal = optional(Make::find($vehicle['make']))->name;
                $modelVal = optional(VehicleModel::find($vehicle['model']))->name;
                $subModelVal = optional(SubModel::find($vehicle['sub_model']))->name;

                // Select Year
                if ($vehicle['year'] == '2020') {
                    $vehicle['year'] = '2019';
                }

                $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbYear_Arrow",
                    $vehicle['year'],
                    "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbYear_DropDown"));

                // Select Make
                $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbMake_Arrow').click();");

                $dusk->waitUntil('!$.active', 20);
                $dusk->waitUntil("$('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbMake_DropDown ul.rcbList').children().length > 0 &&
                        $('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbMake_DropDown ul.rcbList > .rcbLoading').length == 0",
                    10);

                $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbMake_Arrow",
                    $makeVal,
                    "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbMake_DropDown"));

                // Select Model
                $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbModel_Arrow').click();");

                $dusk->waitUntil('!$.active', 20);
                $dusk->waitUntil("$('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbModel_DropDown ul.rcbList').children().length > 0 &&
                    $('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbModel_DropDown ul.rcbList > .rcbLoading').length == 0",
                    10);

                $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbModel_Arrow",
                    $modelVal,
                    "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbModel_DropDown"
                ));

                // Select SubModel
                $dusk->waitUntil('!$.active', 40);
                $dusk->waitUntil("$('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbBodyStyle_DropDown ul.rcbList').children().length > 0 &&
                    $('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbBodyStyle_DropDown ul.rcbList > .rcbLoading').length == 0",
                    10);


                $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbBodyStyle_Arrow').click();
                    
                $('body').find('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleControl{$vehicleIndex}_cbBodyStyle_DropDown ul.rcbList').children().each(function(i, el) {
                    if (el.innerText.includes('$subModelVal')) {
                        el.click();
                    }
                });");
            }

            $vehicleDriver = $vehicleIndex <= $driversCount ? $vehicleIndex - 1 : 0;
            if ($driversCount > 1) {
                $vehicleDriver += 1;
            }

            $dusk->script("
                document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_DriverControl{$vehicleIndex}_cbOperator_Arrow').click();
                    
                $('body').find('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_DriverControl{$vehicleIndex}_cbOperator_DropDown ul.rcbList').children().each(function(i, el) {
                    if (i == $vehicleDriver) { el.click() };
            });");

            if (isset($vehicle['usage'])) {
                $vehicleUsage = $vehicle['usage'] == 'commute' ? "To/From Work" : ucwords($vehicle['usage']);
                $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleUseInput{$vehicleIndex}_cbUse_Arrow",
                    $vehicleUsage,
                    "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleUseInput{$vehicleIndex}_cbUse_DropDown"
                ));

                if ($vehicle['usage'] == 'commute') {
                    $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleUseInput{$vehicleIndex}_tbWorkMiles",
                        '12',
                        "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_VehicleUseInput{$vehicleIndex}_tbWorkMiles_ClientState"
                    ));
                }
            }

            $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_AnMiles{$vehicleIndex}",
                '12,000',
                "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_AnMiles{$vehicleIndex}_ClientState"
            ));

            // Marking This None on Vehicle Page hides the Collusion Deductions on next page!!!
//            if ($vehicle['coverage'] == 'none') {
//                $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_PD{$vehicleIndex}_Arrow",
//                    'No',
//                    "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_PD{$vehicleIndex}_DropDown"
//                ));
//            } else {
                $dusk->script(self::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_PD{$vehicleIndex}_Arrow",
                    $vehicle['coverage'] == 'none' ? 'No' : 'Yes',
                    "ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle{$vehicleIndex}_PD{$vehicleIndex}_DropDown"
                ));
//            }

            if ($vehicleIndex < $vehiclesCount) {
                $dusk->script("$(`input[type='submit'][value='Add Additional Vehicle']`).click()");
                $dusk->script("$('#__tab_ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle".($vehicleIndex + 1)."').click()");
            }
        }

        $dusk->script("$(`input[type='submit'][value='Continue']`).click()");

        /*
        |--------------------------------------------------------------------------
        | Coverage Page
        |--------------------------------------------------------------------------
        */
        if ($dusk->script('return document.getElementById("ctl00_ContentPlaceHolder1_lbHeading").innerHTML')[0] !== 'Current Insurance') {
            var_dump("Failed to go Current Insurance Page");
            $dusk->dump();
            throw new \Exception("Failed to go Current Insurance Page");
        }


        $dusk->script(self::selectDropdownJavascript('ctl00_ContentPlaceHolder1_CurrentInsurance1_cbCurrentInsurance_Arrow', 'Not Currently Insured', 'ctl00_ContentPlaceHolder1_CurrentInsurance1_cbCurrentInsurance_DropDown'));

        switch ($request->coverage) {
            case 'basic':
                $dusk->script('document.getElementById("ctl00_ContentPlaceHolder1_rbCov1").click()');
                break;
            case 'better':
                $dusk->script('document.getElementById("ctl00_ContentPlaceHolder1_rbCov3").click()');
                break;
            case 'best':
                $dusk->script('document.getElementById("ctl00_ContentPlaceHolder1_rbCov4").click()');
                break;
        }

        $dusk->script(self::selectDropdownJavascript('ctl00_ContentPlaceHolder1_PackageDisc1_Arrow', 'No', 'ctl00_ContentPlaceHolder1_PackageDisc1_DropDown'));
        $dusk->script(self::selectDropdownJavascript('ctl00_ContentPlaceHolder1_CreditCheck1_Arrow', 'Yes', 'ctl00_ContentPlaceHolder1_CreditCheck1_DropDown'));

        $date = now()->format('Y-m-d-H-i-s');
        $dusk->script(self::parseJavascript("ctl00_ContentPlaceHolder1_EffDt1_dateInput", $date, "ctl00_ContentPlaceHolder1_EffDt1_dateInput_ClientState"));

        $coverage = $request->vehicles_details[0]['coverage'];
        if ($coverage == 'none') {
            $dropdownExists = $dusk->script("return $('#ctl00_ContentPlaceHolder1_COMP1_DropDown').length")[0] > 0;
            if ($dropdownExists) {
                $dusk->script(self::selectDropdownJavascript('ctl00_ContentPlaceHolder1_COMP1_Arrow', "No Coverage", 'ctl00_ContentPlaceHolder1_COMP1_DropDown'));
            }
        } else {
            $coverage = $coverage == "1000" ? "$1,000" : "$" . $coverage;
            $dusk->script(self::selectDropdownJavascript('ctl00_ContentPlaceHolder1_COMP1_Arrow', $coverage, 'ctl00_ContentPlaceHolder1_COMP1_DropDown'));
            $dusk->script(self::selectDropdownJavascript('ctl00_ContentPlaceHolder1_COLL1_Arrow', $coverage, 'ctl00_ContentPlaceHolder1_COLL1_DropDown'));
        }

        $dusk->script("$(`input[type='submit'][value='Get Rate Quotes']`).click()");

        /*
        |--------------------------------------------------------------------------
        | Quote Page
        |--------------------------------------------------------------------------
        */
        $dusk->waitUntil('$("#ctl00_ContentPlaceHolder1_dQuoteSuccess").length', 60);

        $tagName = $dusk->script("return $($($('#ctl00_ContentPlaceHolder1_UpdatePanel1 .inner_container .is tbody tr')[1]).find('td')[0]).children()[0].tagName")[0];
        if ($tagName === 'IMG') {
            $dusk->script('
            var url = $($($("#ctl00_ContentPlaceHolder1_UpdatePanel1 .inner_container .is tbody tr")[1]).find("td")[0]).children()[0].getAttribute("src");
            
            fetch(url)
                .then(r => r.blob())
                .then(blob => new Promise(( resolve ) => {
                    var reader = new FileReader();
                    reader.onload = function() {
                        var b64 = reader.result.replace(/^data:.+;base64,/, "");
                        resolve( b64 );
                    };
                    reader.readAsDataURL(blob);
                }))
                .then( b64 => {
                    $("body").append(`<input id="b64string" value="${b64}">`);
                });
            ');

            $dusk->waitUntil("!$.active", 30);

            $b64Img = $dusk->script("return document.getElementById('b64string').value;")[0];

            $companyName = self::companyNameFromImgId($b64Img);

        } else {
            $companyName = $dusk->script("return $($($('body').find('#ctl00_ContentPlaceHolder1_UpdatePanel1 .inner_container .is tbody tr')[1]).find('td')[0]).children()[0].innerText")[0];
        }

        $totalQuotedAmount = $dusk->script("return document.getElementById('ctl00_ContentPlaceHolder1_rptCompanyPremiums_ctl01_lblCol2Premium').innerText")[0];

        $quote = [
            "Company" => str_replace('amp;', '', $companyName),
            "Total Quoted Amount" => $totalQuotedAmount
        ];

        return response()->json(['quote' => $quote]);
    }


    public function store(Request $request) {
        $quote = Quote::create([
            'email' => $request['details']['email'] ?? null,
            'address'  => $request['details']['address'] ?? null,
            'phone'  => $request['details']['phone'] ?? null,
            'total_quoted_amount' => $request['rates']['total_quoted_amount'] ?? null,
            'quote_company' => $request['rates']['company'] ?? null,
            'zip' => $request['zip'],
            'coverage' => $request['coverage']
        ]);

        foreach ($request->vehicles_details as $vehicle) {
            $quote->vehicles()->create([
                'vin_no' => $vehicle['vin_no'],
                'year' => $vehicle['year'],
                'make' => optional(Make::find($vehicle['make']))->name ?? $vehicle['make'],
                'model' => optional(VehicleModel::find($vehicle['model']))->name ?? $vehicle['model'],
                'sub_model' => optional(SubModel::find($vehicle['sub_model']))->name ?? $vehicle['sub_model'],
                'coverage' =>$vehicle['coverage'],
                'usage' => $vehicle['usage'] ?? null,
                'alarm' => $vehicle['alarm'] ?? null
            ]);
        }

        foreach ($request->drivers_details as $driver) {
            $quote->drivers()->create([
                'first_name' => isset($driver['first_name']) ? strtoupper($driver['first_name']) : null,
                'last_name' => isset($driver['last_name']) ? strtoupper($driver['last_name']) : null,
                'dob' => $driver['dob'],
                'good_driver' => $driver['good_driver'] ?? null,
                'good_student_age' => $driver['good_student'] ?? null,
                'license_no' => isset($driver['license_no']) ? strtoupper($driver['license_no']) : null,
                'license_status' => $driver['license_status'],
                'licensing_status' => $driver['licensing_status'],
                'marital_status' => $driver['marital_status'],
                'sr_22' => $driver['sr_22'] ?? null,
                'spouse_is_driver' => $driver['spouse_is_driver'] ?? false,
                'state' => $driver['state'] ?? null,
                'wife_dob' => $driver['wife_dob'] ?? null,
                'wife_first_name' => isset($driver['wife_first_name']) ? strtoupper($driver['wife_first_name']) : null,
                'wife_license_status' => $driver['wife_license_status'] ?? null,
                'wife_licensing_status' => $driver['wife_licensing_status'] ?? null,
                'wife_sr_22' => $driver['wife_sr_22'] ?? null,
            ]);
        }

        Notification::route('mail', env('SYSTEM_NOTIFICATION_EMAIL'))
                        ->notify(new QuoteCreated($quote));

        return response()->json(['quote' => $quote]);
    }

    public function update($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));
        $quote->total_quoted_amount = $request['rates']['total_quoted_amount'] ?? null;
        $quote->quote_company = $request['rates']['company'] ?? null;
        $quote->save();

        return response()->json(['success' => true]);
    }


    public static function parseJavascript($inputElId, $value, $objElId = null) {
        $jsString = "
            document.getElementById('$inputElId').value = '$value';
    
            var objEl = document.getElementById('$objElId')
            var obj = JSON.parse(objEl.value);
           
            obj.valueAsString = '$value';
            objEl.value = JSON.stringify(obj);   
        ";
//obj.lastSetTextBoxValue = '$value';
        return $jsString;
    }

    public static function selectDropdownJavascript($arrowEl, $value, $parentId = null) {
        if ($parentId) {
            $parentId = "#$parentId";
        }
        $jsString = "
            document.getElementById('$arrowEl').click();
            
            $('$parentId ul.rcbList').children().each(function(i, el) {
                if (el.innerText == '$value') {
                    el.click();
                }
            });
        ";

        return $jsString;
    }

    public static function companyNameFromImgId($encodedInput) {
        $filePaths = Storage::files('logos');

        $companyName = "Insurance Company";

        foreach ($filePaths as $filePath) {;
            $fullPath = storage_path("app/$filePath");

            if (base64_encode(file_get_contents($fullPath)) == $encodedInput) {
                $baseName = basename($filePath);
                $companyName = str_replace(['.gif', '.jpg'], '', $baseName);
            }
        }
        return $companyName;
    }
}
