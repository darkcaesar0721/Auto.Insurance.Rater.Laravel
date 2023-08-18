<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WpImportUser;
use App\ClientHeight;
use App\ClientEyes;
use App\ClientClass;
use App\ClientSex;
use App\ClientCountry;
use App\ReferralSource;

class ApiController extends Controller
{
	public function covertData(Request $request){
		$params = $request->all();
        $importUser = new WpImportUser();

		if (isset($params['Profile_Picture'])) {
			$importUser['profile_picture'] = $params['Profile_Picture'];
		}
		if (isset($params['Signature'])) {
			$importUser['signature_picture'] = $params['Signature'];
		}
		if (isset($params['Membership_Card'])) {
			$importUser['membership_card'] = $params['Membership_Card'];
		}
		if (isset($params['Membership_Number'])) {
			$importUser['membership_number'] = $params['Membership_Number'];
		}
		elseif (isset($params['Order_Number'])) {
			$importUser['membership_number'] = $params['Order_Number'];
		}
		if (isset($params['License_Number'])) {
			$importUser['license_number'] = str_replace('#', '', $params['License_Number']);
		}
		if (isset($params['Expires_On'])) {
			$term = explode(' ', $params['Expires_On'])[0];
			switch ($term) {
				case 1:
					$importUser['expires_on'] = "year";
					break; 
				case 3: 
					$importUser['expires_on'] = "3_years";
					break;
				case 5:
					$importUser['expires_on'] = "5_years";
					break;
				case 10: 
					$importUser['expires_on'] = "10_years";
					break;
				default: 
					$importUser['expires_on'] = $params['Expires_On'];
			}
		}
		if(isset($params['Given_Names'])) {
			$importUser['given_names'] = $params['Given_Names'];
		}
		if (isset($params['Surname'])) {
			$importUser['surname'] = $params['Surname'];
		} 
		if (isset($params['Address_Line_1'])) {
			$importUser['address_line_1'] = $params['Address_Line_1'];
		}
		if (isset($params['Address_Line_2'])) {
			$importUser['address_line_2'] = $params['Address_Line_2'];
		}
		if (isset($params['Nationality'])) {
			$importUser['nationality'] = $params['Nationality'];

			$nationality = null;
			if (strlen($importUser['nationality']) == 2) {
				$nationality = ClientCountry::where('alpha2', $params['Nationality'])->first();
			}
			elseif (strlen($importUser['nationality']) == 3) {
				$nationality = ClientCountry::where('alpha3', $params['Nationality'])->first();
			}
			if ($nationality) {
				$importUser['nationality_id'] = $nationality['id'];
			}
		}
		if (isset($params['Issued_On'])) {
			$importUser['issued_on'] = $params['Issued_On'];
		}
		if (isset($params['Date_of_Birth'])) {
			$importUser['birth'] = $params['Date_of_Birth'];
		}
		if (isset($params['Email'])) {
			$importUser['email'] = $params['Email'];
		}
		if (isset($params['Phone'])) {
			$importUser['phone'] = str_replace('+1 (','', $params['Phone']);
			$importUser['phone'] = str_replace(') ', '-', $importUser['phone']);
		}
		if (isset($params['Order_Date'])) {
			$importUser['order_date'] = $params['Order_Date'];
		}

		if (isset($params['Height'])) {
			$height = ClientHeight::where('height', 'like', trim($params['Height'], '”').'%')->first();
			if ($height) {
				$importUser['height_id'] = $height -> id;
			}
		}
		
		if (isset($params['Eyes'])) {
			$eyes = ClientEyes::where('alpha3', $params['Eyes'])->first();
			if ($eyes) {
				$importUser['eyes_id'] = $eyes -> id;
			}
			else {
				$eyes = ClientEyes::where('eyes', 'OTHER')->first();
				if ($eyes) {
					$importUser['eyes_id'] = $eyes -> id;
				}
			}
		}

		if (isset($params['Class'])) {
			$userClass = ClientClass::where('class', 'like', $params['Class'].'%')->first();
			if ($userClass) {
				$importUser['class_id'] = $userClass -> id;
			}
		}

		if (isset($params['Sex'])){
			$sex = ClientSex::where('sex', 'like', $params['Sex'].'%')->first();
			if ($sex) {
				$importUser['sex_id'] = $sex -> id;
			}
		}

        $importUser['order_type'] = isset($params['Order_Type']) ? $params['Order_Type'] : '';
        $importUser['plan'] = isset($params['Plan']) ? $params['Plan'] : '';

        $importUser['shipping_type'] 		= $params['Shipping_Type'] 		?? '';
		$importUser['ordered'] 				= $params['Ordered'] 			?? '';
		$importUser['agent_first_name'] 	= $params['Agent_First_Name'] 	?? '';
		$importUser['agent_last_name'] 		= $params['Agent_Last_Name'] 	?? '';
		$importUser['agent_address_one'] 	= $params['Agent_Address_One'] 	?? '';
		$importUser['agent_address_two'] 	= $params['Agent_Address_Two'] 	?? '';
		$importUser['agent_city'] 			= $params['Agent_City'] 		?? '';
		$importUser['agent_state'] 			= $params['Agent_State'] 		?? '';
		$importUser['agent_zip'] 			= $params['Agent_Zip'] 			?? '';
		$importUser['agent_tax_id']	 		= $params['Agent_Tax_ID'] 		?? '';
		$importUser['agent_phone'] 			= $params['Agent_Phone'] 		?? '';
		$importUser['agent_fax'] 			= $params['Agent_Fax'] 			?? '';
		
		$importUser -> save();

        file_put_contents('api-test.txt', $request->all());
        file_put_contents('api-test-json.txt', json_encode($request->all()));

        return response()->json([
            'success' => true
        ]);
	}
}
