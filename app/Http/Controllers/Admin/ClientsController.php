<?php

namespace App\Http\Controllers\Admin;

use App\Hasher;
use App\Http\Controllers\Controller;
use App\Quote;
use App\QuoteDriver;
use App\QuoteVehicle;
use App\SubModel;
use App\VehicleModel;
use App\Year;
use App\ClientSex;
use App\ClientClass;
use App\ClientCountry;
use App\ClientHeight;
use App\ClientEyes;
use App\PolicyTypes;
use App\PreferredContactMethods;
use App\ClientTypes;
use Illuminate\Http\Request;
use Nette\Utils\Image;
use App\Clients;
use App\ReferralSource;
use App\States;
use App\Company;
use App\ClientPolicy;
use App\Report;
use App\ClientAutoClub;
use App\ClientLicenseOnly;
use Auth;
use Socialite;
use File;
use Storage;
use App\PdfDownload;
use App\AutoclubCsv;
use App\User;
use App\PDFData;
use App\PaymentMethod;
use App\ClientNumber;

class ClientsController extends Controller
{   
    public function index() {
        $clients = Clients::getClientsList();
        $formNamesAutoClub = [
            "INSTALLMENT BILL"
        ]; 
        return view('back-office.clients')
            ->with('formNamesAutoClub', $formNamesAutoClub);
    }

    public function edit($clientId, Request $request) {
        $client = Clients::find($clientId);

        //$clientPolicy = ClientPolicy::selectClientPolicy($clientId);
        $formNames = [
            "Referral Agreement",
            "Referral Endorsement",
            "Direct Purchase",
            "Referral Payment Receipt",
            "Multi Car ID",
            "Insurance Identification Card",
            "Direct Policy",
            "SP Direct Policy",
            "Quote Letter",
            "SP Quote Letter",
            "Membership Applications"
        ];
        
        $formNamesAutoClub = [];
        

        // if ($client->autoClub && $client->autoClub->member_id) {
            $formNamesAutoClub[] = ["ADD OFFER FORM", "OFFER", PdfDownload::where('client_id', $clientId)->where('pdf_id', 'OFFER')->first()];
        // }
        $formNamesAutoClub[] = ["INSTALLMENT BILL", "AUTOCLUB-1", PdfDownload::where('client_id', $clientId)->where('pdf_id', 'AUTOCLUB-1')->first()];
        
        $formLicense = [
            "License"
        ];
        
        $licensePdfs = PdfDownload::where('client_id', $clientId)->where('pdf_id', 'LICENSE-1')->get();
        if (!$client) {
            return redirect()->back()->withErrors('Client could not be found')->withInput();
        }
        return view('back-office.new-client')
            ->with('clientTypes', ClientTypes::get())
            ->with('policyTypes', PolicyTypes::get())
            ->with('referralSource', ReferralSource::get())
            ->with('companies', Company::orderBy('company_name', 'asc')->get())
            ->with('contactMethods', PreferredContactMethods::get())
            ->with('states', States::get())
            ->with('client', $client)
            ->with('clientSex',ClientSex::get())
            ->with('clientClasses',ClientClass::get())
            ->with('clientCountries',ClientCountry::orderBy('country')->get())
            ->with('clientEyes',ClientEyes::get())
            ->with('clientHeights',ClientHeight::get())
            // ->with('autoClubPdfs', $autoClubPdfs)
            ->with('licensePdfs', $licensePdfs)
            //->with('clientPolicy', $clientPolicy)
            ->with('formNames', $formNames)
            ->with('formNamesAutoClub', $formNamesAutoClub)
            ->with('formLicense', $formLicense)
            ->with('files', $this->listFiles($parentId = 'client-' . $client->client_number, 's3'))
            ->with('agents', User::select('id', 'name')->get())
            ->with('paymentMethods', PaymentMethod::get());
    }

    public function saveMainInfo(Request $request) {
        $validationRules = [
            'current_address_line_1' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'current_address_line_1' => 'required|min:5',
            'current_address_zip_code' => 'required|digits:5',
            'current_address_address_city' => 'required',
            'home_phone' => 'required|digits:10',
            'preferred_contact_method_id' => 'required',
            'source' => 'required',
            'agent_id' => 'required',
            'email_address' => 'required|email|unique:clients,email_address',
            'cell_phone' => 'required|min:12|max:12|unique:clients,cell_phone',
            'client_number' => 'required|min:11|max:11'
        ];

        $clientId = $request->get('client_id');
        if ($clientId) {
            $validationRules['email_address'] .= ',' . $clientId . ',id';
            $validationRules['cell_phone'] .= ',' . $clientId . ',id';
            $validationRules['client_number'] .= ',' . $clientId . ',id';
        }

        if ($request->get('no_email')) {
            $validationRules['email_address'] = '';
            $request->merge(['email_address' => '']);
        }

        $initialCellPhone = $request->get('cell_phone');
        $initialHomePhone = $request->get('home_phone');
        $request->merge([
            'home_phone' => str_replace('-', '', $initialHomePhone),
            'no_email' => $request->get('no_email') ? 1 : 0,
            'mailing_address' => $request->has('mailing_address') ? 1 : 0,
            'auto_club' => $request->has('auto_club') ? 1 : 0,
            'auto_club_license_only' => $request->has('auto_club_license_only') ? 1 : 0
        ]);

        $initialCellPhone2 = '';
        if (!empty($request->get('cell_phone_2'))) {
            $validationRules['cell_phone_2'] = 'required|digits:10';

            $initialCellPhone2 = $request->get('cell_phone_2');
            $request->merge([
                'cell_phone_2' => str_replace('-', '', $initialCellPhone2),
            ]);
        }
        if($request->get('auto_club') || $request->get('auto_club_license_only') || $request->get('policy_type_id') == PolicyTypes::TYPE_AUTO_CLUB || $request->get('policy_type_id') == PolicyTypes::TYPE_LICENSE_ONLY) {
            $re = '/^(((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01])\/((19|[2-9]\d)\d{2}))|((0[13456789]|1[012])\/(0[1-9]|[12]\d|30)\/((19|[2-9]\d)\d{2}))|(02\/(0[1-9]|1\d|2[0-8])\/((19|[2-9]\d)\d{2}))|(02\/29\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/';
            $validationRules['client_date_of_birth'] = ['required', 'regex:' . $re];
        } else {
             $request->merge([
                'client_sex_id' => null,
                'client_class_id' => null,
                'client_height_id' => null,
                'client_eyes_id' => null,
                'client_country_id' => null,
                'client_date_of_birth' => null
            ]);
        }
        if($request->get('auto_club') && !$request->get('auto_club_license_only')){
             $request->merge([
                'client_class_id' => null,
                'client_height_id' => null,
                'client_eyes_id' => null,
                'client_country_id' => null
            ]);
        }
        if ($request->get('mailing_address')) {
            $validationRules['mailing_address_line_1'] = 'required|min:5';
            $validationRules['mailing_address_zip_code'] = 'required|digits:5';
            $validationRules['mailing_address_city'] = 'required';
            $validationRules['mailing_address_state_id'] = 'required';
        }

        if ($request->get('policy_type_id') == PolicyTypes::TYPE_COMMERCIAL) {
            $validationRules['business_name'] = 'required';
            $request->merge([
                'auto_club' => 0,
                'auto_club_license_only' => 0
            ]);
        }

        $reqClientNumber = $request->get('client_number');
        if ($request->get('client_type_id') == ClientTypes::TYPE_CLIENT) {
            if ($clientId) {
                $client = Clients::find($clientId);
                $firstPart = date("Y") . '-';
                // in case if client type wasnt previously set to client and we change it during editing client
                if (!empty($client->client_number)) {
                    $firstPart = substr($client->client_number, 0, -6);
                    $request->merge([
                        'client_number' => $firstPart . $reqClientNumber
                    ]);
                } else {
                    $clientNumber = date("Y") . '-' . $reqClientNumber;
                    $request->merge([
                        'client_number' => $clientNumber
                    ]);
                }
            } else {
                $clientNumber = date("Y") . '-' . $reqClientNumber;
                $request->merge([
                    'client_number' => $clientNumber
                ]);
            }
        } else {
            $validationRules['client_number'] = '';
            $request->merge([
                'client_number' => ''
            ]);
        }

        $client_no = $request->get('client_number');
        if ($client_no) {
            $number = ClientNumber::where('client_id', $clientId)
                                ->first();
            if (($number && $number->number !== $client_no) || !$number) {
                $validationRules['client_number'] .= '|unique:client_numbers,number';  
            }
        }

        if (!$request->get('international')) {
            $validationRules += [
                'current_address_address_state_id' => 'required'
            ];
        }

        $validator = \Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $request->merge([
                'client_number' => $reqClientNumber
            ]);

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('selectedTab', 'info');
        }

        $verifyPhoneNumber = true;
        if ($clientId) {
            $currentCellPhone = Clients::getFieldValue($clientId, 'cell_phone');
            // means no verification if the phone number is same
            $verifyPhoneNumber = $currentCellPhone != $request->get('cell_phone');
        }

        $request->merge([
            'home_phone' => $initialHomePhone
        ]);

        if ($verifyPhoneNumber) {
            $request->merge([
                'verification' => 0
            ]);
        }

        if (!empty($request->get('cell_phone_2'))) {
            $request->merge([
                'cell_phone_2' => $initialCellPhone2
            ]);
        }

        $first_name = ucwords($request->get('first_name'));
        $middle_name = ucwords($request->get('middle_name'));
        $last_name = ucwords($request->get('last_name'));
        $suffix = ucwords($request->get('suffix'));
        $current_address_line_1 = ucwords($request->get('current_address_line_1'));
        $current_address_line_2 = ucwords($request->get('current_address_line_2'));
        $current_address_address_city = ucwords($request->get('current_address_address_city'));
        $mailing_address_line_2 = ucwords($request->get('mailing_address_line_2'));
        $mailing_address_city = ucwords($request->get('mailing_address_city'));
        $additional_insured_first_name = ucwords($request->get('additional_insured_first_name'));
        $additional_insured_middle_name = ucwords($request->get('additional_insured_middle_name'));
        $additional_insured_last_name = ucwords($request->get('additional_insured_last_name'));
        $additional_insured_suffix = ucwords($request->get('additional_insured_suffix'));

        $request->merge([
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'suffix' => $suffix,
            'current_address_line_1' => $current_address_line_1,
            'current_address_line_2' => $current_address_line_2,
            'current_address_address_city' => $current_address_address_city,
            'mailing_address_line_2' => $mailing_address_line_2,
            'mailing_address_city' => $mailing_address_city,
            'additional_insured_first_name' => $additional_insured_first_name,
            'additional_insured_middle_name' => $additional_insured_middle_name,
            'additional_insured_last_name' => $additional_insured_last_name,
            'additional_insured_suffix' => $additional_insured_suffix
        ]);
        $message = $clientId ? 'The Client has been updated' : 'The Client has been created';
        if ($clientId) {
            Clients::updateClientInfo(
                $request->all()
            );
        } else {
            $newClient = Clients::addNewClient(
                $request->all()
            );
            $clientId = $newClient->id;
        }

        if ($verifyPhoneNumber) {
            return redirect('/admin/clients/verify-phone/' . $clientId);
        }

        return redirect('/admin/clients/edit/' . $clientId)->with('success', $message);
    }

    public function verifyPhone(Request $request, $clientId) {
        $client = Clients::find($clientId);

        if ($request->isMethod('post')) {
            $validationRules = [
                'verification_code' => 'required|in:' . $client->verification_code
            ];

            $validator = \Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $client->verification = 1;
            $client->save();

            return redirect('/admin/clients/edit/' . $clientId)->with('success', $message = 'Clients Phone has been verified');
        }

        
        $client->generateVerificationCode();

        return view('back-office.verify-phone')->with('client', $client);
    }

    public function savePolicy(Request $request) {
        $validationRules = [
            'policy_number.*' => 'required',
            'premium.*' => 'required',
            'co_fees.*' => 'required',
            'broker_fee.*' => 'required',
            // 'policy_referral_source.*' => 'required',
            'company_down_payment.*' => 'required|numeric',
            'monthly_payment.*' => 'required|numeric'
        ];

        $validator = \Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('selectedTab', 'policy');
        }

        $clientId = $request->get('client_id');
        $message = '';
        if ($clientId) {
            $message = 'The Client has been updated';
        } else {
            $newClient = Clients::addNewClient([]);
            $clientId = $newClient->id;
            $message = 'The Client has been created';
        }

        ClientPolicy::whereClientId($clientId)->delete();
        foreach ($request->policy_number as $idx => $policyNumber) {
            $create = [
                'client_id' => $clientId,
                'company_list_id' => $request->get('company_list_id')[$idx],
                'effective_date' => date('Y-m-d', strtotime($request->get('effective_date')[$idx])),
                'term' => $request->get('term')[$idx],
                'expiration_date' => date('Y-m-d', strtotime($request->get('expiration_date')[$idx])),
                'policy_number' => $request->get('policy_number')[$idx],
                'premium' => $request->get('premium')[$idx],
                'co_fees' => $request->get('co_fees')[$idx],
                'broker_fee' => $request->get('broker_fee')[$idx],
                'agency_total' => $request->get('agency_total')[$idx],
                'company_total' => $request->get('company_total')[$idx],
                'is_endorsement' => $request->has('is_endorsement.' . $idx) ? 1 : 0,
                'paymentm_method_option' => $request->get('paymentm_method_option')[$idx],
                'check_no' => $request->get('check_no')[$idx],
                'company_down_payment' => $request->get('company_down_payment')[$idx],
                'monthly_payment' => $request->get('monthly_payment')[$idx],
                'referral_fee_option' => $request->get('referral_fee_option')[$idx],
                'amount' => $request->get('amount')[$idx],
                'total_down_payment' => $request->get('total_down_payment')[$idx],
                'referral_source_id' => $request->get('policy_referral_source')[$idx]
            ];

            if (
                $request->get('paymentm_method_option')[$idx] != 'referral pay' &&
                $request->get('paymentm_method_option')[$idx] != 'referral-customer pay'
            ) {
                unset($create['referral_source_id']);
                unset($create['referral_fee_option']);
                if ($request->get('paymentm_method_option')[$idx] != 'referral-customer pay') {
                    unset($create['check_no']);
                }
            }

            ClientPolicy::create($create);
        }
        return redirect('/admin/clients/edit/' . $clientId)->with('success', $message);
    }

    public function saveAttachment(Request $request) {
        if ($request->has('attachment_file_1')) {
            $validationRules = [
                'attachment_file_1' => 'mimes:jpeg,bmp,png,gif,svg,pdf|max:2048'
            ];

            $validator = \Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('selectedTab', 'attach');
            }

            $clientId = $request->get('client_id');
            $message = '';
            if ($clientId) {
                $message = 'The Client has been updated';
            } else {
                $newClient = Clients::addNewClient([]);
                $clientId = $newClient->id;
                $message = 'The Client has been created';
            }
            $client = Clients::find($clientId);
            $folderName = 'client-' . $client->client_number;
            $this->createFile($request->file('attachment_file_1'), $folderName, 's3');

            return redirect('/admin/clients/edit/' . $clientId)->with('success', $message);
        } elseif ($request->get('client_id')) {
            return redirect('/admin/clients/edit/' . $request->get('client_id'));
        }

        return redirect('/admin/clients');
    }

    public function generateForm(Request $request, $clientId, $pdfId) {
        $pdfPath = PDFData::generateForm($pdfId, $clientId);

        return redirect()->to(
            $pdfPath
        );
    }


    public function new(Request $request) {
        $formNames = [
            "Referral Agreement",
            "Referral Endorsement",
            "Direct Purchase",
            "Referral Payment Receipt",
            "Multi Car ID",
            "Insurance Identification Card",
            "Direct Policy",
            "SP Direct Policy",
            "Quote Letter",
            "SP Quote Letter",
            "Membership Application"
        ];
        $formNamesAutoClub = [
            "INSTALLMENT BILL"
        ];
        $formLicense = [
            "License"
        ];

        $autoClubPdfs = [];
        $licensePdfs = [];
        return view('back-office.new-client')
            ->with('clientTypes', ClientTypes::get())
            ->with('policyTypes', PolicyTypes::get())
            ->with('referralSource', ReferralSource::get())
            ->with('companies', Company::orderBy('company_name', 'asc')->get())
            ->with('contactMethods', PreferredContactMethods::get())
            ->with('states', States::get())
            ->with('client', null)
            ->with('formNames', $formNames)
            ->with('formNamesAutoClub', $formNamesAutoClub)
            ->with('formNamesAutoClub', $formLicense)
            ->with('clientSex',ClientSex::get())
            ->with('clientClasses',ClientClass::get())
            ->with('clientCountries',ClientCountry::get())
            ->with('clientEyes',ClientEyes::get())
            ->with('clientHeights',ClientHeight::get())
            ->with('autoClubPdfs', $autoClubPdfs)
            ->with('licensePdfs', $licensePdfs)
            ->with('agents', User::select('id', 'name')->get())
            //->with('clientPolicy', null)
            ->with('files', []);
    }

    public function getFolderId($folderName, $bucket) {
        $results = Storage::disk($bucket)->files($folderName);    
        if (isset($results[0])) {
            return $folderName;
        } else {
            $this->createFolder($folderName, $bucket);
            return($folderName);
        }
    }

    public function createFile($file, $folderName, $bucket) {
        $folderName = $this->getFolderId($folderName, $bucket);
        $fileName = $file->getClientOriginalName();
        $content = File::get($file);
        Storage::disk($bucket)->put($folderName .'/'. $fileName, $content,'public');
    }

    public function listFiles($folderName, $bucket){
        if ($folderName === 'client-') {
            return [];
        }
        
        $files = Storage::disk($bucket)->files($folderName);
        $results = [];
        foreach ($files as $file) {
            $fileData = Storage::disk($bucket)->getMetadata($file);
            $fileName = $fileData['basename'];
            $result = [
                'name' => $fileName,
                'url' => Storage::disk($bucket)->url($file),
                'created_at' => date('m/d/Y', Storage::disk($bucket)->lastModified($file)),
                'folder' => $folderName
            ];
            array_push($results, $result);
        }
        return $results;
    }
    public function deleteFile($folder, $bucket, $file){
        if (Auth::user()->is_admin) {
            $path = $folder.'/'.$file;
            Storage::disk($bucket)->delete($path);
        }
        if($bucket == 's3'){
            return redirect('/admin/clients/');
        } else if ($bucket == 's3-company'){
            return redirect('/admin/company/');
        } else if ($bucket == 's3-referral') {
            return redirect('/admin/referral/');
        }
    }

    function createFolder($folder_name, $bucket){
        Storage::disk($bucket)->makeDirectory($folder_name);
        return $folder_name;
    }

    public function getAddPolicy(Request $request) {
        $client = Clients::find($request->get('clientId'));

        return response()->json([
            'policy' => view('back-office.partials.client.policy-single', [
                'policyIndex' => $request->get('policyIndex'),
                'policy' => null,
                'companies' => Company::orderBy('company_name', 'asc')->get(),
                'referralSource' => ReferralSource::orderBy('referral_company', 'asc')->get(),
                'endorsementAvailable' => $client && $client->policies()->count() >= 1 ? true : false
            ])->render()
        ]);
    }

    public function report(Request $request) {
        return view('back-office.client-report')
            ->with('companies', Company::orderBy('company_name', 'asc')->get())
            ->with('referralSource', ReferralSource::get())
            ->with('policyTypes', PolicyTypes::get());
    }

    public function deleteClient($clientId) {
        if (Auth::user()->is_admin) {
            ClientsController::deleteAllAttachments($clientId);
            Clients::deleteClient($clientId);
            ClientPolicy::deleteClientPolicy($clientId);
            ClientLicenseOnly::deleteLicenseOnly($clientId);
            ClientAutoClub::deleteAutoClub($clientId);
        }
        return redirect('/admin/clients');
    }

    public function saveAutoClub(Request $request) {
        $client = Clients::where('id', $request->client_id)->first();
        $validationRules = [
            'member_id' => 'required',
            'premium' => 'required',
            'co_fees' => 'required',
            'down_payment' => 'required',
            'referral_amount' => 'required',
            'monthly_payment' => 'required',
            'company_total' => 'required'
        ];
        
        $validator = \Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('selectedTab', 'auto-club');
        }
        $dataAutoClub = [
            "client_id" => $request->client_id,
            "member_id" => $request->member_id,
            "payment_method_id" => $request->payment_method_id,
            "referral_source_id" => $request->referral_source_id,
            "check_no" => $request->check_no,
            "term" => $request->term,
            "effective_date" => $request->effective_date,
            "expiration_date" => $request->expiration_date,
            "premium" => $request->premium,
            "co_fees" => $request->co_fees,
            "down_payment" => $request->down_payment,
            "referral_amount" => $request->referral_amount,
            "monthly_payment" => $request->monthly_payment,
            "company_total" => $request->company_total,
            "referral_source_id" => $request->referral_source_id,
            "check_no" => $request->check_no
        ];

        $pm = PaymentMethod::where('id', $request->payment_method_id)->first();
        if ($pm) {
            $paymentAutoMethod = $pm->alias;
        }
        else {
            $paymentAutoMethod = 'direct cash';
        }
        $referal = ReferralSource::where('referral_company' , 'DISCOUNT AUTO CLUB - LATIN AUTO CLUB')
                ->first(); // Your agent section should always default to Discount auto club 
        switch ($paymentAutoMethod) {
            case 'direct cash':
                $dataAutoClub['check_no'] = null;
                if ($referal) {
                    $dataAutoClub['referral_source_id'] = $referal->id;
                }
                else {
                    $dataAutoClub['referral_source_id'] = null;
                }
                $dataAutoClub['referral_amount'] = 0;
                break;
            case 'direct credit card':
                $dataAutoClub['check_no'] = null;
                if ($referal) {
                    $dataAutoClub['referral_source_id'] = $referal->id; 
                }
                else {
                    $dataAutoClub['referral_source_id'] = null;
                }
                $dataAutoClub['referral_amount'] = 0;
                break;
            case 'direct other':
                $dataAutoClub['check_no'] = null;
                if ($referal) {
                    $dataAutoClub['referral_source_id'] = $referal->id; 
                }
                else {
                    $dataAutoClub['referral_source_id'] = null;
                }
                $dataAutoClub['referral_amount'] = 0;
                break;
            case 'referral pay':
                $dataAutoClub['check_no'] = null;
                break;
        }
        $autoClub = ClientAutoClub::whereClientId($client->id)->first();
        if ($autoClub) {
            $autoClub->update($dataAutoClub);
        } else {
            ClientAutoClub::create($dataAutoClub);
        }
        
        $message = 'The Client has been updated';
        return redirect('/admin/clients/edit/' . $request->client_id)->with('success', $message)->with('selectedTab', 'auto-club');;
    }

    public function saveLicenseOnly(Request $request) {
        $validationRules = [
            'license_number' => 'required',
            'price' => 'required',
            'ship_fee' => 'required',
            'total_cost' => 'required',
            'photo' =>  'image|max:5120|mimes:jpeg,png',
            'sign' =>   'image|max:5120|mimes:jpeg,png'
        ];

        $validator = \Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('selectedTab', 'license-only');
        }

        $dataLicenseOnly = $request->except('_token');

        if (request()->hasFile('photo')) {
            $imageName = 'photo.jpg';
            request() -> photo -> move(public_path('images/userImages/'.$dataLicenseOnly['client_id']."/"), $imageName);
            $dataLicenseOnly['photo'] = 'images/userImages/'.$dataLicenseOnly['client_id']."/".$imageName;

            $imagePath = 'images/userImages/'.$dataLicenseOnly['client_id']."/".$imageName;

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
        }

        if (request()->hasFile('sign')) {
            $imageName = 'sign.jpg';
            request() -> sign -> move(public_path('images/userImages/'.$dataLicenseOnly['client_id']."/"), $imageName);

            $imagePath = 'images/userImages/'.$dataLicenseOnly['client_id']."/".$imageName;

            $img = Image::fromFile(public_path($imagePath));

            $width = $img->getWidth();
            $height = $width/2.23;

            if (request() -> sign -> getClientOriginalExtension() == "png") {
                 $blank = Image::fromBlank($width, $img->getHeight(), Image::rgb(255, 255, 255, 0));
                 $blank->place($img);
                 $img = $blank;
            }

            $img->crop('50%','50%', $width, $height);
            
            $maxWidth = 550;

            if ($width > $maxWidth) {
                $img->resize($maxWidth, ($maxWidth/2.23));
            }

            $img->save(public_path($imagePath, 85, Image::JPEG));

            $dataLicenseOnly['sign'] = $imagePath;
        }

        $pm = PaymentMethod::where('id', $request->payment_method_id)->first();
        if ($pm) {
            $paymentMethod = $pm->alias;
        }
        switch ($paymentMethod) {
            case 'direct cash':
                $dataLicenseOnly['referral_source_id'] = null;
                break;
            case 'direct credit card':
                $dataLicenseOnly['referral_source_id'] = null;
                break;
            case 'direct other':
                $dataLicenseOnly['referral_source_id'] = null;
                break;
            case 'referral pay':
                break;
        }
        $clientId = $request->get('client_id');
        $licenseOnly = ClientLicenseOnly::whereClientId($clientId)->first();
        if ($licenseOnly) {
            $licenseOnly->update($dataLicenseOnly);
        } else {
            ClientLicenseOnly::create($dataLicenseOnly);
        }

        $message = 'The Client has been updated';
        return redirect('/admin/clients/edit/' . $clientId)
                ->with('success', $message)
                ->with('selectedTab', 'license-only');
    }

    public function generateCSV(Request $request, $client_id) {
        if (!isset($client_id)) {
            return redirect()->back();
        }
        $client = Clients::where('id', $client_id)->first();
        if ($client->autoClub) {
            
            $terminate = $request->get('terminate');

            $header = array(
                    'GRP_ER_CODE',
                    'PRD_CD',
                    'UNIQUE_ID',
                    'ACT_EFF_DT',
                    'INACTIVE_DT',
                    'REL_TYPE',
                    'FIRST_NAME',
                    'MI',
                    'LAST_NAME',
                    'SUFFIX',
                    'BIRTH_DATE',
                    'GENDER',
                    'ADR_LINE_1',
                    'ADR_LINE_2',
                    'CITY',
                    'STATE',
                    'ZIP_CD',
                    'EMAIL',
                    'PHONE_NO',
                    'ACTION_CODE',
                    'PAY_FREQUENCY'
            );

            if ($terminate) {
                $header[] = "TERMINATED";
            }

            $acctDate = date_parse_from_format('m/d/Y', $client->autoClub->effective_date);
            
            if ($acctDate['day'] > 20) {
                $acctDate['day'] = 1;
                $acctDate['month'] = $acctDate['month'] == 12 ? 1 : $acctDate['month'] + 1;
            }

            $acctDate = new \DateTime($acctDate['year'].'-'.$acctDate['month'].'-'.$acctDate['day']);
            $dateOfBirth = new \DateTime($client->client_date_of_birth);

            $state = States::where('id', $client->current_address_address_state_id)->first();

            $term = '';

            switch ($client->autoClub->term) {
                case '3_months':
                    $term = "Q";
                    break;
                case '6_months':
                    $term = "S";
                    break;
                case 'monthly':
                    $term = "M";
                    break;
                case 'year' :
                    $term = "A";
                    break;
            } 

            $data = array(
                '348697',                                                                       // GRP_ER_CODE
                '14154',                                                                        // PRD_CD
                strtoupper($client->autoClub->member_id),                                       // UNIQUE_ID
                $acctDate->format('Ymd'),                                                       // ACT_EFF_DT
                '',                                                                             // INACTIVE_DT
                'P',                                                                            // REL_TYPE 
                strtoupper($client->first_name),                                                // FIRST_NAME
                $client->middle_name ? strtoupper(substr($client->middle_name, 0,1)) : '',      // MI
                strtoupper($client->last_name),                                                 // LAST_NAME
                strtoupper($client->suffix),                                                    // SUFFIX
                $dateOfBirth->format('Ymd'),                                                    // BIRTH_DATE
                substr($client->getSex(), 0, 1),                                                // GENDER
                strtoupper(str_replace(',', '', $client->current_address_line_1)),              // ADR_LINE_1
                strtoupper(str_replace(',', '', $client->current_address_line_2)),              // ADR_LINE_2
                strtoupper($client->current_address_address_city),                              // CITY
                $state ? $state->name : '',                                                     // STATE
                $client->current_address_zip_code,                                              // ZIP_CD
                strtoupper($client->email_address),                                             // EMAIL
                str_replace('-', '', $client->cell_phone),                                      // PHONE_NO
                '',                                                                             // ACTION_CODE
                $term                                                                           // PAY_FREQUENCY
            );

            $csvDir = "csv";
            $fileName = $data[2].'_'.date('Ymd').'_ALSV'.".csv";
            $csvPath = $csvDir. "/". $fileName;

            $csv = AutoclubCsv::where('client_id', $client_id)->first();

            if ($terminate) {
                $data[] = date('Ymd');
                
                if ($csv && file_exists(public_path($csv->name))) {
                    $csvPath = $csv->name;
                    $fileName = str_replace($csvDir.'/', '', $csvPath);
                }
            }
            else if ($csv && file_exists($csv->name)) {
                unlink(public_path($csv->name));
                $csv->delete();
                $csv = new AutoclubCsv();
            }
            else {
                $csv = new AutoclubCsv();   
            }

            
            if (!file_exists(public_path($csvDir))) {
                mkdir(public_path($csvDir));
            }

            $fp = fopen(public_path($csvPath), 'w');

            fputcsv($fp, $header);
            fputcsv($fp, $data);

            fclose($fp);

            $file = public_path($csvPath);
            $remote_file = 'Inbound files from Insura/'. $fileName;

            $ftp = Storage::createFtpDriver([
                                             'host'     => 'ftp.1sas.com',
                                             'username' => 'Insura',
                                             'password' => '4Wz&j9wG',
                                             'port'     => '21',
                                             'timeout'  => '30',
                                         ]);
            $success = $ftp->put($remote_file, file_get_contents($file));

            if ($success) {
                if ($terminate) {
                    $csv->terminated_at = date('Y-m-d H:i:s');
                    $csv->update();
                }
                else {
                    $csv->name = $csvPath;
                    $csv->client_id = $client_id;
                    $csv->save();
                }
            }
            

            $message = $success ? 'CSV has been uploaded' : 'CSV not sent, something went wrong';
           return redirect()->back()->with('success', $message);
        }
    }

    public function trackingAjax(Request $request){
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        if ($request->get('search'))
            $search = $request->get('search')['value'];
        return Clients::trackingAjax($start,$length,$search,$draw);
    }

    public function deleteAllAttachments($clientId) {
        $client = Clients::find($clientId);
        if ($client && $client->client_number) {
            $folderName = 'client-' . $client->client_number;
            Storage::disk('s3')->deleteDirectory($folderName);
        }
    }
}
