<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SubModel;
use App\VehicleModel;
use App\Year;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Array_;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

class PhoneNumberValidationController extends Controller
{
    public function index() {
        try {
            $twilio = new Client("AC6095868a3b0024a0e5ce6a2ad9f37f57", "f4c8bb5f424e56297226d5bd28a7c35c");

            $number = $twilio->lookups
                ->phoneNumbers(request('phone_no'))
//                ->phoneNumbers($test)
                ->fetch(
                    array("type" => "carrier")
                );

            $countryCode = $number->countryCode;
        } catch (ConfigurationException $e) {
            return response()->json($e->getMessage(), 400);
        }

        if ($countryCode !== "US") {
            return response()->json($countryCode, 401);
        }

        return response()->json($countryCode);

    }
}
