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
use App\Company;
use Auth;
use Storage;
use File;

class CompanyController extends Controller
{   
    public function index() {
        $companies = Company::orderBy('company_name', 'asc')->get();
        return view('back-office.company')->with([
            'companies' => $companies
        ]);
    }

    public function new() {
        if (Auth::user()->is_admin) {
            return view('back-office.new-company')->with('company', null)->with('files', []);
        }
        return redirect()->back();
    }

    public function saveCompanyInfo(Request $request) {
        $validationRules = [
            'company_name' => 'required|unique:companies,company_name',
            'broker_id' => 'required',
            'toll_free' => 'required',
            'claims_phone'=> 'required',
            'fax' => 'required',
            'email' => 'required|email',
            'website' => 'required'
        ];
        $companyId = $request->get('id');
        if ($companyId) {
            $newCompanyName = $request->get('company_name');
            $oldCompanyName = Company::find($companyId);
            $oldCompanyName = $oldCompanyName->company_name;
            if ($newCompanyName==$oldCompanyName) {
                $validationRules['company_name'] = 'required';
            } else {
                $validationRules['company_name'] = 'required|unique:companies,company_name';
            }
        } else {
            $validationRules['company_name'] = 'required|unique:companies,company_name';
        }

        $validator = \Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('selectedTab', 'info');
        }
        $companyId = $request->get('id');
        if ($companyId) {
            Company::updateCompanyInfo(
                $request->all()
            );
            $message = 'The Company has been updated';
        } else {
            $newCompany = Company::addNewCompany(
                $request->all()
            );
            $companyId = $newCompany->id;
            $message = 'The Company has been created';
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
            $company = Company::find($companyId);
            $folderName = 'company - ' . $company->company_name;
            (new ClientsController)->createFile($request->file('attachment_file_1'), $folderName, 's3-company');
        }
        return redirect('/admin/company/edit/' . $companyId)->with('success', $message);
    }

    public function companyEdit($companyId, Request $request) {
        $company = Company::find($companyId);
        return view('back-office.new-company')
            ->with('companyId', $companyId)
            ->with('company', $company)
            ->with('files', (new ClientsController)->listFiles($parentId = 'company - ' . $company->company_name,'s3-company'));
    }

    public function companyWebsite(Request $request, $companyId) {
        $company = Company::find($companyId);
        if ($company->website) {
            return redirect($company->website);
        }
        return redirect()->back();
    }

    public function deleteCompany($companyId) {
        if (Auth::user()->is_admin) {
            Company::deleteCompany($companyId);
        }
        return redirect('/admin/company');
    }
}
