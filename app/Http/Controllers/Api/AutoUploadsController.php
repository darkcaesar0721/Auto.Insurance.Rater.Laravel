<?php

namespace App\Http\Controllers\Api;

use App\Hasher;
use App\Http\Controllers\Controller;
use App\Quote;
use App\SubModel;
use App\VehicleModel;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Array_;

class AutoUploadsController extends Controller
{
    /*
     * Driver Licenses
     */
    public function driversLicenses($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $request->file->store('auto/' . $quote->hash_id . '/drivers');

        return response()->json([
            'original-name' => $request->file->getClientOriginalName(),
            'hash_name' => $request->file->hashName()
        ]);
    }

    public function deleteDriversLicense($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $directory = 'auto/' . $quote->hash_id . '/drivers';
        Storage::delete($directory . '/' . $request->filename);

        return response()->json();
    }

    /*
     * Vehicle Registrations
     */
    public function vehiclesRegistration($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $request->file->store('auto/' . $quote->hash_id . '/vehicles-registrations');

        return response()->json([
            'original-name' => $request->file->getClientOriginalName(),
            'hash_name' => $request->file->hashName()
        ]);
    }

    public function deleteVehiclesRegistration($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $directory = 'auto/' . $quote->hash_id . '/vehicles-registrations';
        Storage::delete($directory . '/' . $request->filename);

        return response()->json();
    }


    /*
     * Vehicle Photos
     */
    public function vehiclePhotos($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $request->file->store('auto/' . $quote->hash_id . '/vehicle-photos');

        return response()->json([
            'original-name' => $request->file->getClientOriginalName(),
            'hash_name' => $request->file->hashName()
        ]);
    }

    public function deleteVehiclePhotos($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $directory = 'auto/' . $quote->hash_id . '/vehicle-photos';
        Storage::delete($directory . '/' . $request->filename);

        return response()->json();
    }

}
