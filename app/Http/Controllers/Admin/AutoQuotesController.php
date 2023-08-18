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
use Illuminate\Http\Request;
use Nette\Utils\Image;
use App\Clients;

class AutoQuotesController extends Controller
{
    public function index() {
        $quotes = Quote::where('is_deleted', false)
                        ->with(['drivers', 'vehicles'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('back-office.auto-quotes')->with([
            'quotes' => $quotes
        ]);
    }

    public function indexDeleted() {
        $quotes = Quote::where('is_deleted', true)
                        ->with(['drivers', 'vehicles'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('back-office.auto-quotes')->with([
            'quotes' => $quotes
        ]);
    }

    public function show($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        return view('back-office.auto-quote')->with([
            'quote' => $quote,
            'isEditing' => false
        ]);
    }

    public function showEdit($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        return view('back-office.auto-quote')->with([
            'quote' => $quote,
            'isEditing' => true
        ]);
    }

    public function update($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $data = $request->all();
        $data['email_verified'] = $request->email_verified == 'true';
        $data['card_authorized'] = $request->card_authorized == 'true';
        $quote->update($data);

        foreach ($request->drivers as $quoteDriverId => $driver) {
            $driverData = $driver;
            $driverData['good_driver'] = $driver['good_driver'] == 'true';
            $driverData['sr_22'] = $driver['sr_22'] == 'true';
            $driverData['spouse_is_driver'] = $driver['spouse_is_driver'] == 'true';
            $driverData['wife_sr_22'] = isset($driver['wife_sr_22']) && $driver['wife_sr_22'] == 'true';

            QuoteDriver::where('quote_id', $quote->id)->where('id', $quoteDriverId)->update($driverData);
        }

        foreach ($request->vehicles as $quoteVehicleId => $vehicle) {
            $vehicleData = $vehicle;
//            $driverData['good_driver'] = $driver['good_driver'] == 'true';
//            $driverData['sr_22'] = $driver['sr_22'] == 'true';
//            $driverData['spouse_is_driver'] = $driver['spouse_is_driver'] == 'true';
//            $driverData['wife_sr_22'] = isset($driver['wife_sr_22']) && $driver['wife_sr_22'] == 'true';

            QuoteVehicle::where('quote_id', $quote->id)->where('id', $quoteVehicleId)->update($vehicleData);
        }

        return redirect("admin/auto/$hashId");
    }

    public function delete($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $quote->is_deleted = true;
        $quote->save();

        return redirect()->back();
    }

    public function destroy($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));
        $quote->delete();

        return redirect()->back();
    }

    public function recover($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $quote->is_deleted = false;
        $quote->save();

        return redirect()->back();
    }

    public function displayImage($hashId, $dir, $img) {
        $storagePath = storage_path("app/auto/$hashId/$dir/$img");

        return response()->file($storagePath);
    }

    public function updateNote($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));
        $quote->note = $request->note;
        $quote->save();

        return response()->json();
    }

    public function convertToClient($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));
        $driver = $quote->drivers->first();
        
        $client = new Clients();
        $client->client_type_id = \App\ClientTypes::TYPE_CLIENT;
        $client->policy_type_id = \App\PolicyTypes::TYPE_PERSONAL;
        $client->current_address_line_1 = $quote->address;
        $client->current_address_zip_code = $quote->zip;
        
        if (isset($quote->email)) {
            $client->email_address = $quote->email;
            $client->no_email = 0;
            $method = \App\PreferredContactMethods::where('name', 'Email')->first();
            if ($method) {
                $client['preferred_contact_method_id'] = $method->id;
            }
        }
        else {
            $client['no_email'] = 1;
        }

        if (isset($quote->phone)) {

            $phone = str_replace('+1 (','', $quote->phone);
            $phone = str_replace(') ', '-', $phone);

            $client['cell_phone'] = $phone;
            $client['home_phone'] = $phone;

            if(!isset($client['preferred_contact_method_id'])) {
                $method = \App\PreferredContactMethods::where('name', 'Mobile Phone')->first();
                if ($method) {
                    $client['preferred_contact_method_id'] = $method->id;
                }
            }
        }

        $client->source = 'input';
        $client->agent_id = \Auth::user()->id;
        $client->language_spoken = 'english';
        $client->notes = $quote->note;
        $client->client_sex_id = 1;
        $client->auto_club = false;
        $client->auto_club_license_only = false;
        
        if ($driver) {
            $client->first_name = $driver->first_name;
            $client->last_name = $driver->last_name;
            $client->client_date_of_birth = $driver->dob;
            
            if (!$client->current_address_address_state_id) {
                if ($driver->state) {
                    $state = \App\States::where('name', $driver->state)->first();
                    if ($state) {
                        $client->current_address_address_state_id = $state->id;
                    }
                }
            }
        }

        $client->client_number = Clients::generateClientNo(true);
        $client->save();

        $policy = new \App\ClientPolicy();
        $policy->client_id = $client->id;

        if ($quote->quote_company) {
            $company = \App\Company::where('company_name', $quote->quote_company)->first();
            if ($company) {
                $policy->company_list_id = $company->id;                
            }
        }

        $policy->effective_date     = new \DateTime(); 
        $policy->expiration_date    = new \DateTime();
        $policy->policy_number      = '';
        $policy->premium            = 0;
        $policy->co_fees            = 0;
        $policy->broker_fee         = 0;
        $policy->monthly_payment    = 0;
        $policy->save();

        return redirect("/admin/clients/edit/".$client->id);
    }

}
