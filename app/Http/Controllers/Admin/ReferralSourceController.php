<?php

namespace App\Http\Controllers\Admin;

use App\Hasher;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ClientsController;
use App\Quote;
use App\QuoteDriver;
use App\QuoteVehicle;
use App\SubModel;
use App\VehicleModel;
use App\Year;
use App\PolicyTypes;
use App\PreferredContactMethods;
use App\ClientTypes;
use Illuminate\Http\Request;
use Nette\Utils\Image;
use App\Clients;
use App\States;
use App\Company;
use App\ClientPolicy;
use App\ReferralSource;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Auth;
use Socialite;
use File;
use Storage;

class ReferralSourceController extends Controller
{
    public function index() {   
        $referralSource = ReferralSource::orderBy('referral_company', 'asc')->get();

        return view('back-office.referral')->with([
            'referralSource' => $referralSource
        ]);
    }

    public function new() {
        if (Auth::user()->is_admin) {
            return view('back-office.new-referral')
                ->with('states', States::get())
                ->with('referralSource', null)
                ->with('files', []);
        }
        return redirect()->back();
    }
    public function saveReferralInfo(Request $request) {
        $validationRules = [
            'referral_first_name' => 'required',
            'referral_address_line_1' => 'required',
            'referral_city' => 'required',
            'referral_state_id' => 'required',
            'referral_zip' => 'required',
            'referral_work' => 'required',
            'referral_cell' => 'required',
            'referral_email' => 'required|email'
        ];

        $referralId = $request->get('id');
        if ($referralId) {
            $newReferralCompany = $request->get('referral_company');
            $oldReferralCompany = ReferralSource::find($referralId);
            $oldReferralCompany = $oldReferralCompany->referral_company;
            if ($newReferralCompany==$oldReferralCompany) {
                $validationRules['referral_company'] = 'required';
            } else {
                $validationRules['referral_company'] = 'required|unique:referral_sources,referral_company';
            }
        } else {
            $validationRules['referral_company'] = 'required|unique:referral_sources,referral_company';
        }

        $validator = \Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('selectedTab', 'info');
        }
        $referralSourceId = $request->get('id');
        if ($referralSourceId) {
            if ($request->referral_website && substr($request->referral_website, 0, 4) !== 'http') {
                $request->merge(['referral_website' => 'http://' . $request->referral_website]);
            }
            ReferralSource::updateReferralSourceInfo(
                $request->all()
            );
            $message = 'The Referral Source has been updated';
        } else {
            $newReferralSource = ReferralSource::addNewReferralSource(
                $request->all()
            );
            $referralSourceId = $newReferralSource->id;
            $message = 'The Referral Source has been created';
        }
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
            $referral = ReferralSource::find($referralSourceId);
            $folderName = 'referral company - ' . $referral->referral_company;
            (new ClientsController)->createFile($request->file('attachment_file_1'), $folderName, 's3-referral');
        }
        return redirect('/admin/referral/edit/' . $referralSourceId)->with('success', $message);
    }

    public function referralEdit($referralSourceId, Request $request) {
        $referralSource = ReferralSource::find($referralSourceId);
        return view('back-office.new-referral')
            ->with('states', States::get())
            ->with('referralSourceId', $referralSourceId)
            ->with('referralSource', $referralSource)
            ->with('files', (new ClientsController)->listFiles($parentId = 'referral company - ' . $referralSource->referral_company,'s3-referral'));
    }

    public function deleteReferral($referralId) {
        if (Auth::user()->is_admin) {
            ReferralSource::deleteReferral($referralId);
        }

        return redirect('/admin/referral');
    }
}