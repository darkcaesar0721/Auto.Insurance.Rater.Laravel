<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Http\Controllers\Api\AutoQuoteController;
use App\Mail\AutoMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return redirect('/auto');
});

Route::get('/Liability-Bodily-Injury-And-Property-Damage', 'DescriptionsController@bodilyInjury');
Route::get('/Comprehensive-And-Collision', 'DescriptionsController@collision');
Route::get('/VIN', 'DescriptionsController@vin');

Route::get('/auto', 'RaterController@index');
Route::get('/auto/quote/{hashId}', 'RaterController@showQuote');
Route::post('/auto/quote/{hashId}', 'RaterController@authorizeCreditCard');
Route::get('/auto/quote/{hashId}/authorized', 'RaterController@authorized');
Route::get('/auto/{any}', 'RaterController@redirectToAuto');

Route::get('/auto/quote/{hashId}/upload', 'UploadsController@showAuto');
Route::get('/auto/quote/{hashId}/upload-success', 'UploadsController@showAutoSuccess');



Route::get('/4lac-api', 'Admin\ApiController@covertData');
Route::post('/4lac-api', 'Admin\ApiController@covertData');

Route::prefix('/admin')->group(function () {
    Route::get('/login/google', 'Auth\LoginController@redirectToGoogleProvider');
    Route::get('/login/google/callback', 'Auth\LoginController@handleProviderGoogleCallback');

    Route::get('/drive', 'Admin\ClientsController@getDrive');

    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');

    Route::get('/logout', 'Auth\LoginController@logout');

    Route::middleware('auth')->group(function() {
        Route::get('/', 'Admin\HomeController@index');
        Route::get('/auto', 'Admin\AutoQuotesController@index');
        Route::get('/clients', 'Admin\ClientsController@index');
        Route::get('/import-clients', 'Admin\ImportClientController@index');
        Route::get('/company', 'Admin\CompanyController@index');
        Route::get('/referral', 'Admin\ReferralSourceController@index');
        Route::get('/reports', 'Admin\ReportsController@index');
        Route::get('/report-data', 'Admin\ReportsController@reportData');

        Route::get('/company/website/{companyId}', 'Admin\CompanyController@companyWebsite');
        Route::get('/clients/add-policy', 'Admin\ClientsController@getAddPolicy');
        Route::get('/company/company-info', 'Admin\ClientsController@addCompanyInfo');
        Route::get('/auto/deleted', 'Admin\AutoQuotesController@indexDeleted')->middleware('admin');
        Route::get('/auto/{hashId}', 'Admin\AutoQuotesController@show');
        
        Route::get('/client/delete/{clientId}', 'Admin\ClientsController@deleteClient');
        Route::get('/client/delete-import/{clientId}', 'Admin\ImportClientController@delete')->middleware('admin');

        Route::get('/clients/generateCSV/{clientId}', 'Admin\ClientsController@generateCSV');

        Route::get('/client/create-import/{clientId}', 'Admin\ImportClientController@create');

        Route::get('/company/delete/{companyId}', 'Admin\CompanyController@deleteCompany');
        Route::get('/referral/delete/{referralId}', 'Admin\ReferralSourceController@deleteReferral');
        
        Route::get('/clients/getPaginator', 'Admin\ClientsController@trackingAjax');
        Route::get('/clients/create', 'Admin\ClientsController@new');
        Route::get('/clients/files/delete/{folder}/{bucket}/{file}', 'Admin\ClientsController@deleteFile')->name('clients.file.delete');
        Route::get('/company/create', 'Admin\CompanyController@new');
        Route::get('/referral/create', 'Admin\ReferralSourceController@new');
        Route::post('/clients/save-main-info', 'Admin\ClientsController@saveMainInfo');
        Route::post('/clients/save-auto-club', 'Admin\ClientsController@saveAutoClub');
        Route::post('/clients/save-license-only', 'Admin\ClientsController@saveLicenseOnly');
        Route::post('/company/save-company-info', 'Admin\CompanyController@saveCompanyInfo');
        Route::post('/referral/save-referral-info', 'Admin\ReferralSourceController@saveReferralInfo');
        Route::post('/clients/save-policy', 'Admin\ClientsController@savePolicy');
        Route::post('/company/website', 'Admin\ClientsController@companyWebsite');
        Route::match(['get', 'post'], '/report', 'Admin\ClientsController@report');

        Route::post('/clients/save-attachment', 'Admin\ClientsController@saveAttachment');
        // Route::post('/clients/save-forms', 'Admin\ClientsController@saveForms');
        Route::get('/clients/generate-form/{clientId}/{pdfId}', 'Admin\ClientsController@generateForm');

        Route::match(['get', 'post'], '/clients/edit/{clientId}', 'Admin\ClientsController@edit');
        Route::match(['get', 'post'], '/clients/verify-phone/{clientId}', 'Admin\ClientsController@verifyPhone');
        Route::match(['get', 'post'], '/company/edit/{companyId}', 'Admin\CompanyController@companyEdit');
        Route::match(['get', 'post'], '/referral/edit/{referralId}', 'Admin\ReferralSourceController@referralEdit');

        Route::post('/auto/{hashId}/note', 'Admin\AutoQuotesController@updateNote');
        Route::get('/auto/{hashId}/edit', 'Admin\AutoQuotesController@showEdit');
        Route::post('/auto/{hashId}/edit', 'Admin\AutoQuotesController@update');

        Route::get('/auto/{hashId}/delete', 'Admin\AutoQuotesController@delete')->middleware('admin');
        Route::get('/auto/{hashId}/import', 'Admin\AutoQuotesController@convertToClient');
        Route::get('/auto/{hashId}/delete-entirely', 'Admin\AutoQuotesController@destroy')->middleware('admin');
        Route::get('/auto/{hashId}/recover', 'Admin\AutoQuotesController@recover')->middleware('admin');
        Route::get('/auto/{hashId}/{dir}/{img}', 'Admin\AutoQuotesController@displayImage');

        Route::get('/users/create-user', 'Admin\UsersController@create'); // middleware in controller
        Route::post('/users/create-user', 'Admin\UsersController@store');
        Route::get('/users', 'Admin\UsersController@index');
        Route::get('/users/{id}/delete', 'Admin\UsersController@delete');
    });
});

Route::prefix('/customer')->group(function () {
    Route::get('/login', 'CustomerController@showLogin');
    Route::post('/login', 'CustomerController@login');
    Route::get('/quote/{hashId}', 'CustomerController@show');
});


//Route::get('/test', function() {
//    $quote = App\Quote::findOrFail(1);
////    return new App\Mail\AutoMail($quote);
//    Mail::to($quote)->send(new AutoMail($quote));
//});
////
////Route::get('/test', function() {
////    $quote = App\Quote::findOrFail(1);
////    dd($quote->files_count);
////});

Route::get('/dusktest', function() {
//dd(AutoQuoteController::companyNameFromImgId(storage_path('app/test2.gif')));
//    new \App\Services\ImageComparer();

//    dd("stop");
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

    if ($dusk->script('return document.getElementById("ctl00_ContentPlaceHolder1_lbHeading").innerHTML')[0] !== 'Driver Information') {
        throw new \Exception("Failed to go Driver Page");
    }

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

    $dob = Carbon::parse('12/10/1990')->format('Y-m-d-H-i-s');
    $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_DOB1', $dob, 'ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_DOB1_ClientState'));

    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Gender1_Arrow', "Male"));
    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_MaritalStatus1_Arrow', "Single"));
    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Driver_Occupation1_cbIndustry_Arrow', "Other"));
    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_Driver_Occupation1_cbOccupation_Arrow', "Other"));
    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcDrivers_tpDriver1_IncidentInput1_cbIncident_Arrow', "No"));

    $dusk->click('input[type="submit"][title="Continue to Vehicle Information"]');

    if ($dusk->script('return document.getElementById("ctl00_ContentPlaceHolder1_lbHeading").innerHTML')[0] !== 'Vehicle Information') {
        throw new \Exception("Failed to go Vehicle Page");
    }

    /*
    |--------------------------------------------------------------------------
    | Vehicles Page
    |--------------------------------------------------------------------------
    */
    // Select Year
    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbYear_Arrow', '2019'));

    // Select Make
    $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_Arrow').click();");

    $dusk->waitUntil('!$.active' , 20);
    $dusk->waitUntil('$("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_DropDown ul.rcbList").children().length > 0 &&
                        $("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_DropDown ul.rcbList > .rcbLoading").length == 0', 10);

    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbMake_Arrow', 'ACURA'));

    // Select Model
    $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_Arrow').click();");

    $dusk->waitUntil('!$.active' , 20);
    $dusk->waitUntil('$("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_DropDown ul.rcbList").children().length > 0 &&
                    $("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_DropDown ul.rcbList > .rcbLoading").length == 0', 10);

    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbModel_Arrow', 'ILX'));

    // Select SubModel
//    $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_Arrow').click();");

    $dusk->waitUntil('!$.active' , 40);
    $dusk->waitUntil('$("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_DropDown ul.rcbList").children().length > 0 &&
            $("#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_DropDown ul.rcbList > .rcbLoading").length == 0', 10);


    $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_Arrow').click();
            
            $('body').find('#ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleControl1_cbBodyStyle_DropDown ul.rcbList').children().each(function(i, el) {
                if (el.innerText.includes('SEDAN 4D - 1')) {
                    el.click();
                }
            });");

    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_DriverControl1_cbOperator_Arrow', 'John Doe'));
    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_VehicleUseInput1_cbUse_Arrow', 'Pleasure'));
    $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_AnMiles1', '12,000', 'ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_AnMiles1_ClientState'));
    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_PD1_Arrow', 'Yes', 'ctl00_ContentPlaceHolder1_tcVehicles_tpVehicle1_PD1_DropDown'));

    $dusk->click('input[type="submit"][name="ctl00$ContentPlaceHolder1$tcVehicles$tpVehicle1$btnContinue1"]');

    if ($dusk->script('return document.getElementById("ctl00_ContentPlaceHolder1_lbHeading").innerHTML')[0] !== 'Current Insurance') {
        $dusk->dump();
        throw new \Exception("Failed to go Current Insurance Page");
    }

    //
    try {
        $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_CurrentInsurance1_cbCurrentInsurance_Arrow', 'Not Currently Insured', 'ctl00_ContentPlaceHolder1_CurrentInsurance1_cbCurrentInsurance_DropDown'));
    } catch (Exception $e) {
        $dusk->dump();
    }


    $dob = Carbon::parse('12/13/2019')->format('Y-m-d-H-i-s');
    $dusk->script(AutoQuoteController::parseJavascript('ctl00_ContentPlaceHolder1_EffDt1_dateInput', $dob, 'ctl00_ContentPlaceHolder1_EffDt1_dateInput_ClientState'));

    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_PackageDisc1_Arrow', 'No', 'ctl00_ContentPlaceHolder1_PackageDisc1_DropDown'));
    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_CreditCheck1_Arrow', 'Yes', 'ctl00_ContentPlaceHolder1_CreditCheck1_DropDown'));

    $dusk->script(AutoQuoteController::selectDropdownJavascript('ctl00_ContentPlaceHolder1_COMP1_Arrow', 'No Coverage', 'ctl00_ContentPlaceHolder1_COMP1_DropDown'));
    $dusk->script("document.getElementById('ctl00_ContentPlaceHolder1_rbCov2').click();");

    $dusk->script('document.getElementById("ctl00_ContentPlaceHolder1_btnContinue").click()');

    // RESULTS

    try {
//        $dusk->waitUntil('!$.active' , 40);
        $dusk->waitUntil('$("#ctl00_ContentPlaceHolder1_dQuoteSuccess").length', 60);
    } catch (Exception $e) {
        $dusk->dump();
    }

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

        $companyName = AutoQuoteController::companyNameFromImgId($b64Img);

    } else {
        $companyName = $dusk->script("return $($($('body').find('#ctl00_ContentPlaceHolder1_UpdatePanel1 .inner_container .is tbody tr')[1]).find('td')[0]).children()[0].innerText");
    }

    $totalQuotedAmount = $dusk->script("return document.getElementById('ctl00_ContentPlaceHolder1_rptCompanyPremiums_ctl01_lblCol2Premium').innerText")[0];

    $quote = [
        "Company" => $companyName,
        "Total Quoted Amount" => $totalQuotedAmount
    ];

    dd($quote);

    $dusk->dump();

});
