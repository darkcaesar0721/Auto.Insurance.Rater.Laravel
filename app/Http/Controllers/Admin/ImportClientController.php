<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WpImportUser;
use App\Clients;
use App\ClientAutoClub;
use App\ClientLicenseOnly;
use App\ClientTypes;
use App\PolicyTypes;
use App\States;
use App\ReferralSource;
use App\PreferredContactMethods;
use Nette\Utils\Image;
use App\ClientCountry;
use App\PaymentMethod;
use App\ClientNumber;

use Auth;

class ImportClientController extends Controller
{
    public function index() { 
    	$clients = WpImportUser::orderBy('created_at', 'desc')
                        ->get();

        return view('back-office.import-clients')->with([
            'clients' => $clients
        ]);
    }

    public function delete($clientId) {
        if (Auth::user()->is_admin) {
            WpImportUser::where('id', $clientId)->first()->delete();
        }
        return redirect('/admin/import-clients');
    }

    public function create($clientId) {
        $wpClient = WpImportUser::where('id', $clientId)->first();

        if ($wpClient) {
            $newClient = new Clients();
            $newClient['first_name'] = $wpClient -> given_names;
            $newClient['last_name'] = $wpClient -> surname;
            if ($wpClient -> address_line_1) {
                // address line 1 goes in following format
                // 12345 Main Street, 13C
                // we're going to explode this line to fill in clients address correctly

                $address = explode(', ', $wpClient -> address_line_1);
                $newClient['current_address_line_1'] = isset($address[0]) ? $address[0] : '';
                $newClient['current_address_line_2'] = isset($address[1]) ? $address[1] : '';
            }


            if ($wpClient -> address_line_2) {
                // address line 2 goes in following format
                // Los Angeles, CA, 90041
                // we're going to explode this line to fill in clients address correctly

                $address = explode(', ', $wpClient -> address_line_2);

                $newClient['current_address_address_city'] = isset($address[0]) ? $address[0] : '';
                if (isset($address[1])) {
                    $state = States::where('name', $address[1])->first();
                    if ($state) {
                        $newClient['current_address_address_state_id'] = $state->id;
                    }
                }
                $newClient['current_address_zip_code'] = isset($address[2]) ? $address[2] : '';
            }

            if (isset($wpClient -> email)) {
                $newClient['email_address'] = $wpClient ->  email;    
                $newClient['no_email'] = 0;
                $method = PreferredContactMethods::where('name', 'Email')->first();
                if ($method) {
                    $newClient['preferred_contact_method_id'] = $method->id;
                }
            }
            else {
                $newClient['no_email'] = 1;
            }

            if (isset($wpClient -> phone)) {
                $newClient['cell_phone'] = $wpClient -> phone;
                $newClient['home_phone'] = $wpClient -> phone;

                if(!isset($newClient['preferred_contact_method_id'])) {
                    $method = PreferredContactMethods::where('name', 'Mobile Phone')->first();
                    if ($method) {
                        $newClient['preferred_contact_method_id'] = $method->id;
                    }
                }
            }
     		$newClient['nationality_id']         = $wpClient -> nationality_id; 
     		$newClient['client_height_id']       = $wpClient -> height_id;
     		$newClient['client_eyes_id']         = $wpClient -> eyes_id;
     		$newClient['client_class_id']        = $wpClient -> class_id;
     		$newClient['client_sex_id']          = $wpClient -> sex_id;
     		$birth = new \DateTime($wpClient -> birth);
            $newClient['client_date_of_birth']   = $birth->format('m/d/Y');

     		$newClient['source'] = "import";
     		$newClient['auto_club'] = 0;
     		$newClient['auto_club_license_only'] = 0;
     		$newClient['client_type_id'] = ClientTypes::TYPE_CLIENT;
     		$newClient['policy_type_id'] = PolicyTypes::TYPE_LICENSE_ONLY;
     		$newClient['mailing_address'] = 0;
     		$newClient['verification'] = 1;
            $autoclub = false;
            $license = false;
     		if ($wpClient->order_type == 'License + Membership') {
                $autoclub = true;
                $license = true;
                $newClient['auto_club_license_only'] = 1;
                $newClient['policy_type_id'] = PolicyTypes::TYPE_AUTO_CLUB;
            } elseif ($wpClient->order_type == 'License Only') {
                $newClient['policy_type_id'] = PolicyTypes::TYPE_LICENSE_ONLY;
                $license = true;
            } elseif ($wpClient->order_type == 'Membership Only') {
                $newClient['policy_type_id'] = PolicyTypes::TYPE_AUTO_CLUB;
                $autoclub = true;
            }

            if (isset($newClient['current_address_address_state_id'])) {
                $country = ClientCountry::where('alpha3', 'USA')->first();
                if ($country) {
                    $newClient['client_country_id'] = $country->id;
                }
            }

            if(\Schema::hasTable('client_numbers')) {
                $newClient->save();
                $newClient->client_number = ClientNumber::generateClientNo(true);
            }
            else {
                $newClient->client_number = ClientNumber::generateClientNo(true);
                $newClient->save();   
            }

     		if ($autoclub) {
                $autoClub = new ClientAutoClub();
                $autoClub['client_id'] = $newClient['id'];
                $autoClub['member_id'] = $wpClient->membership_number;

                $autoClub['payment_method_id'] = 1;
                $referalDefault = ReferralSource::where('referral_company' , 'DISCOUNT AUTO CLUB - LATIN AUTO CLUB')
                                           ->first(); // Your agent section should always default to Discount auto club 
                
                if ($referalDefault)  {
                    $autoClub['referral_source_id'] = $referalDefault->id;
                }

                if (isset($wpClient['ordered']) && strpos($wpClient['ordered'], 'Agent') !== false) {
                    $method = PaymentMethod::where('alias', 'referral pay')->first();
                    $agentName = str_replace('Agent:::', '', $wpClient['ordered']);
                    $agentName = str_replace(':::on', '', $agentName);
                    $agentName = str_replace(':::', '', $agentName);
                    $autoClub['payment_method'] = $method->id;
                    $query = ReferralSource::where('referral_company', $agentName);
                    
                    if ($query->count() == 0 && $wpClient['agent_tax_id']) {
                        $format1 = $wpClient['agent_tax_id'];
                        $format2 = $format1;

                        if (strlen($format1) === 9) {
                             $format1 = substr($format1, 0, 3) . '-' . substr($format1, 3, 2) . '-' . substr($format1, 5, 4);
                             $format2 = substr($format2, 0, 2) . '-' . substr($format2, 2, 7);
                        }

                        $query->orWhere('referral_tax_id', $wpClient['agent_tax_id'])
                                ->orWhere('referral_tax_id', $format1)
                                ->orWhere('referral_tax_id', $format2);
                    }
                    if ($query->count() == 0 && $wpClient['agent_phone']) {
                        $agent_phone = str_replace('+1 (','', $wpClient['agent_phone']);
                        $agent_phone =  str_replace(') ', '-', $agent_phone);
                        
                        if (strpos($agent_phone, '-') === false && strlen($agent_phone) == 10) {
                            $agent_phone = substr($agent_phone, 0, 3) . '-' . substr($agent_phone, 3, 3) . '-' . substr($agent_phone, 6, 4);
                        }
                        $query->orWhere('referral_work', $agent_phone);
                        if ($query->count() === 1) {
                            $newClient->appendNote('Auto Club agent found by phone number, please, check that the information below matches the Referral Source:
' . $wpClient->getAgentInfo());
                        }
                    }
                    $referal = $query->get();
                    if ($referal && count($referal) === 1) {
                        $autoClub['referral_source_id'] = $referal[0]->id;
                    }
                    else {
                        $autoClub['referral_source_id'] = $referalDefault->id;
                        $newClient->appendNote('Auto Club agent not found:
' . $wpClient->getAgentInfo());
                    }
                }
                
                $effectiveDate = date('d-m-Y');
                $autoClub['effective_date'] = date('m/d/Y');
                
                if (isset($wpClient -> issued_on)) {
                    $issuedOn = new \DateTime($wpClient -> issued_on);
                    $effectiveDate =  $issuedOn->format('d-m-Y');
                    $autoClub['effective_date'] = $issuedOn->format('m/d/Y');
                }

                $premium = 0;
                $coFees = 0;
                $downPayment = 0;
                $amount = 0;
                $monthlyPayment = 0;
                $companyTotal = 0;

                $date = new \DateTime($effectiveDate);
                switch ($wpClient->plan) {
                    case 'Silver':
                        $autoClub['term'] = 'monthly';
                        $date->modify('+1 month');
                        $premium = 19.95;
                        $coFees = 25.00;
                        $downPayment = 44.95;
                        $amount = 8.99;
                        $monthlyPayment = 19.95;
                        $companyTotal = 44.95;
                        break;
                    case 'Gold':
                        $autoClub['term'] = '3_months';
                        $date->modify('+3 month');
    //                    $autoClub['expiration_date'] = date('d/m/Y', strtotime($autoClub['effective_date'] . ' +3 month'));
                        $premium = 59.95;
                        $coFees = 15.00;
                        $downPayment = 74.95;
                        $amount = 14.99;
                        $monthlyPayment = 59.95;
                        $companyTotal = 74.95;
                        break;
                    case 'Platinum':
                        $autoClub['term'] = '6_months';
                        $date->modify('+6 month');
    //                    $autoClub['expiration_date'] = date('d/m/Y', strtotime($autoClub['effective_date'] . ' +6 month'));
                        $premium = 119.95;
                        $coFees = 10.00;
                        $downPayment = 129.95;
                        $amount = 25.99;
                        $monthlyPayment = 119.95;
                        $companyTotal = 129.95;
                        break;
                    case 'Diamond':
                        $autoClub['term'] = 'year';
                        $date->modify('+12 month');
    //                    $autoClub['expiration_date'] = date('d/m/Y', strtotime($autoClub['effective_date'] . ' +12 month'));
                        $premium = 249.95;
                        $coFees = 0;
                        $downPayment = 249.95;
                        $amount = 50.00;
                        $monthlyPayment = 249.95;
                        $companyTotal = 249.95;
                        break;
                }

                $autoClub['expiration_date'] = $date->format('m/d/Y');

                $autoClub['premium'] = $premium;
                $autoClub['co_fees'] = $coFees;
                $autoClub['down_payment'] = $downPayment;
                $autoClub['referral_amount'] = $autoClub['referral_source_id'] != $referalDefault->id ? $amount : 0;
                $autoClub['monthly_payment'] = $monthlyPayment;
                $autoClub['company_total'] = $companyTotal;

                $autoClub->save();
            }

     		if ($license) {
                $newClientLicense = new ClientLicenseOnly();
                $newClientLicense['client_id'] = $newClient['id'];
                $newClientLicense['license_number'] = $wpClient -> license_number ? 'MWL'.$wpClient -> license_number : 'MWL'. $wpClient['membership_number'];
                $newClientLicense['term'] = $wpClient -> expires_on ?? 'year';

                if (!$wpClient -> issued_on) {
                    $wpClient -> issued_on = date('m/d/Y');
                }

                $date = new \DateTime($wpClient -> issued_on);
                
                $newClientLicense['effective_date'] = $date -> format('Y/m/d');

                switch ($newClientLicense['term']) {
                    case 'year':
                        $date->modify('+1 year');
                        break;
                    case '3_years':
                        $date->modify('+3 year');
                        break;
                    case '5_years':
                        $date->modify('+5 year');
                        break;
                    case '10_years':
                        $date->modify('+10 year');
                        break;
                }
                $newClientLicense['expiration_date'] = $date->format('m/d/Y');

                $newClientLicense['payment_method_id'] = 1;

                if (isset($wpClient['ordered']) && strpos($wpClient['ordered'], 'Agent') !== false) {
                    $method = PaymentMethod::where('alias', 'referral pay')->first();
                    $agentName = str_replace('Agent:::', '', $wpClient['ordered']);
                    $agentName = str_replace(':::on', '', $agentName);
                    $agentName = str_replace(':::', '', $agentName);
                    $newClientLicense['payment_method_id'] = $method->id;
                    
                    $query = ReferralSource::where('referral_company', $agentName);
                    
                    if ($query->count() == 0 && $wpClient['agent_tax_id']) {
                        $format1 = $wpClient['agent_tax_id'];
                        $format2 = $format1;

                        if (strlen($format1) === 9) {
                             $format1 = substr($format1, 0, 3) . '-' . substr($format1, 3, 2) . '-' . substr($format1, 5, 4);
                             $format2 = substr($format2, 0, 2) . '-' . substr($format2, 2, 7);
                         }

                        $query->orWhere('referral_tax_id', $wpClient['agent_tax_id'])
                                ->orWhere('referral_tax_id', $format1)
                                ->orWhere('referral_tax_id', $format2);
                    }
                    if ($query->count() == 0 && $wpClient['agent_phone']) {
                        $agent_phone = str_replace('+1 (','', $wpClient['agent_phone']);
                        $agent_phone =  str_replace(') ', '-', $agent_phone);
                        
                        if (strpos($agent_phone, '-') === false && strlen($agent_phone) == 10) {
                            $agent_phone = substr($agent_phone, 0, 3) . '-' . substr($agent_phone, 3, 3) . '-' . substr($agent_phone, 6, 4);
                        }
                        $query->orWhere('referral_work', $agent_phone);
                        if ($query->count() === 1) {
                            $newClient->appendNote('License agent found by phone number, please, check that the information below matches the Referral Source:
' . $wpClient->getAgentInfo());
                        }
                    }
                    $referal = $query->get();

                    if ($referal && count($referal) === 1) {
                        $newClientLicense['referral_source_id'] = $referal[0]->id;
                    }
                    else {
                        $referal = ReferralSource::where('referral_company', 'DISCOUNT AUTO CLUB - LATIN AUTO CLUB')->first();
                        if ($referal) {
                            $newClientLicense['referral_source_id'] = $referal->id;
                        }
                        $newClient->appendNote('License agent not found:
' . $wpClient->getAgentInfo());
                    }
                }

                $newClientLicense['price'] = 35;
                $newClientLicense['ship_fee'] = 0;
                if ($wpClient -> shipping_type && strpos($wpClient['shipping_type'], "FEDEX") !== false) {
                    $newClientLicense['ship_fee'] = 19;
                }
                $newClientLicense['total_cost'] = $newClientLicense['price'] + $newClientLicense['ship_fee'];

                if (!file_exists('images/userImages/'.$newClient['id'].'/')) {
                    mkdir('images/userImages/'.$newClient['id'].'/');
                }

                $imagePath = 'images/userImages/'.$newClient['id']."/photo.jpg";

                if (isset($wpClient['profile_picture'])) {
                    $file = file_get_contents($wpClient['profile_picture']);
                    $extension = "";
                    $is_image = true;
                    if (preg_match('/<img[^>]+src="([^">]+)"/',$file, $result)) {
                        if (count($result) > 1 && strlen($result[1]) > 1) {
                            $file = file_get_contents($result[1]);
                            $path_info = pathinfo($result[1]);
                            $extension = $path_info['extension'];
                        }
                        else 
                        {
                            $is_image = false;    
                        }
                    }
                    else {
                        $is_image = false;
                        // dd("Error, not image found in url ". $wpClient['profile_picture']);
                    }
                    if ($is_image && ($extension == "png" || $extension == "jpg" || $extension == "jpeg")) {
                        file_put_contents(public_path($imagePath), $file);

                        $img = Image::fromFile(public_path($imagePath));

                        $width = $img->getWidth();
                        $height = $img->getHeight();

                        $maxWidth = 550;

                        if ($width < $height) {
                            $img->crop('50%','50%', $width, $width);
                            if ($width > $maxWidth) {
                                $img->resize($maxWidth, $maxWidth);
                            }
                        }
                        else if ($height < $width) {
                            $img->crop('50%','50%', $height, $height);
                            if ($height > $maxWidth) {
                                $img->resize($maxWidth, $maxWidth);
                            }
                        }

                        $img->save(public_path($imagePath, 85, Image::JPEG));

                        $newClientLicense['photo'] = $imagePath;
                    }
                }

                $imagePath  = 'images/userImages/'.$newClient['id']."/sign.jpg";

                if (isset($wpClient['signature_picture'])) {
                    $is_image = true;
                    $file = file_get_contents($wpClient['signature_picture']);
                    $extension = "";
                    if (preg_match('/<img[^>]+src="([^">]+)"/',$file, $result)) {
                        if (count($result) > 1 && strlen($result[1]) > 1) {
                            $file = file_get_contents($result[1]);
                            $path_info = pathinfo($result[1]);
                            $extension = $path_info['extension'];
                        }
                        else {
                            $is_image = false;
                        }
                    }
                    else {
                        $is_image = false;
                        //dd("Error, not image found in url ". $wpClient['signature_picture']);
                    }

                    if ($is_image && ($extension == "png" || $extension == "jpg" || $extension == "jpeg")) {
                        file_put_contents(public_path($imagePath), $file);

                        $img = Image::fromFile(public_path($imagePath));

                        $width = $img->getWidth();
                        $height = $width/2.23;

                        $blank = Image::fromBlank($width, $img->getHeight(), Image::rgb(255, 255, 255, 0));
                        $blank->place($img);
                        $img = $blank;

                        $img->crop('50%','50%', $width, $height);

                        $maxWidth = 550;

                        if ($width > $maxWidth) {
                            $img->resize($maxWidth, ($maxWidth/2.23));
                        }

                        $img->save(public_path($imagePath, 85, Image::JPEG));

                        $newClientLicense['sign'] = $imagePath;
                    }
                }
                $newClientLicense->save();
            }

            // $wpClient -> delete();
     		return redirect("/admin/clients/edit/".$newClient['id']);
    	}
    	else {
    		return redirect("/admin/import-clients/");
    	}
    }
}
