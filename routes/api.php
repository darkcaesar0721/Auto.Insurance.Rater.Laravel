<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/auto/years', 'Api\AutoController@getYears');
Route::get('/auto/years/{year}/makes', 'Api\AutoController@getMakes');
Route::get('/auto/makes/{makeId}/models', 'Api\AutoController@getModels');
Route::get('/auto/models/{modelId}/sub-models', 'Api\AutoController@getSubModels');

Route::post('/auto/vin-details', 'Api\AutoController@vinDetails');

Route::post('/auto/ask-quote', 'Api\AutoQuoteController@askQuote');
Route::post('/auto/store', 'Api\AutoQuoteController@store');
Route::post('/auto/quote/{quoteId}/update', 'Api\AutoQuoteController@update');

Route::get('/validate-address', 'Api\AddressValidationController@index');
Route::get('/validate-phone', 'Api\PhoneNumberValidationController@index');

/*
 * Upload/Delete Auto Files
 */
Route::post('/auto/quote/{quoteId}/upload/drivers-licenses', 'Api\AutoUploadsController@driversLicenses');
Route::post('/auto/quote/{quoteId}/upload/drivers-licenses/delete', 'Api\AutoUploadsController@deleteDriversLicense');
Route::post('/auto/quote/{quoteId}/upload/vehicles-registration', 'Api\AutoUploadsController@vehiclesRegistration');
Route::post('/auto/quote/{quoteId}/upload/vehicles-registration/delete', 'Api\AutoUploadsController@deleteVehiclesRegistration');
Route::post('/auto/quote/{quoteId}/upload/vehicle-photos', 'Api\AutoUploadsController@vehiclePhotos');
Route::post('/auto/quote/{quoteId}/upload/vehicle-photos/delete', 'Api\AutoUploadsController@deleteVehiclePhotos');