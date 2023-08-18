<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SubModel;
use App\VehicleModel;
use App\Year;
use duncan3dc\Laravel\Dusk;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Array_;

class AddressValidationController extends Controller
{
    public function index() {
        $dusk = new Dusk();

        /*
        |--------------------------------------------------------------------------
        | Initial Page
        |--------------------------------------------------------------------------
        */
        $dusk->visit("https://secure.consumerratequotes.com/consumer/QuoteStart.aspx?id=56195&StartPage=Default");

        $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_cbLine_Arrow', "Personal Auto"));
        $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tbZipcode', request('AddressLine2'), 'ctl00_ContentPlaceHolder1_tbZipcode_ClientState'));

        $dusk->click('input[type="submit"]');

        $dusk->script(AutoQuoteController::parseJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_tbAddress", request('AddressLine1'), "ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_tbAddress_ClientState"));

        $dusk->script(AutoQuoteController::selectDropdownJavascript("ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Gender1_Arrow", 'Male'));

        $dusk->script('$(`input[type="submit"][value="Continue"]`).click()');

        $answer = $dusk->script("
            if ($('#ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_lbAddressValid').length > 0) {
                if ($('#ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_lbAddressValid')[0].style.color === 'red') {
                    return $('#ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_lbAddressValid')[0].innerText
                }
                
                return $('#ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_AddressInput1_lbAddressValid')[0].innerText
            }
            return 'not-validated';
        ")[0];

        if ($answer == "The submitted street address has been verified.") {
            $response = [
                'is_validated' => true,
                'response' => $answer
            ];
        } else {
            $response = [
                'is_validated' => false,
                'response' => $answer
            ];
        }

        return response()->json($response);
    }
}
