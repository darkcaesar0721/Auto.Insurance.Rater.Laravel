<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SubModel;
use App\VehicleModel;
use App\Year;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Array_;

class AutoController extends Controller
{
    public function getYears() {
        $years = Year::get()->pluck('name')->toArray();
        array_unshift($years, '2020');

        return response()->json(['years' => $years]);
    }

    public function getMakes($year) {
        if ($year == '2020') {
            $year = '2019';
        }

        $year = Year::where('name', $year)->first();
        $makes = $year->makes;

        return response()->json(['makes' => $makes]);
    }

    public function getModels($makeId) {
        $models = VehicleModel::where('make_id', $makeId)->get();

        return response()->json(['models' => $models]);
    }

    public function getSubModels($modelId) {
        $subModels = SubModel::where('vehicle_model_id', $modelId)->get();

        return response()->json(['subModels' => $subModels]);
    }

    public function vinDetails() {
        $year = optional(Year::where('name', request()->year)->first());

        switch (request()->make) {
            case 'MERCEDES-BENZ':
                $makeRequest = 'MERCEDES';
                break;

            default:
                $makeRequest = request()->make;
                break;
        }

        $makeModel = optional(optional($year->makes())->where('name', 'LIKE', '%' . $makeRequest . '%'))->first();

        /*
         * Model Cases
        */
        $modelCases = $this->getModelCases(request()->model);

        $vehicleModelModel = optional(optional(optional($makeModel)->vehicleModels())->where(function($q) use ($modelCases) {
            $q->whereIn('name', $modelCases);
        }))->first();

        /*
         * Options
         */
        $makeOptions = optional($year)->makes;
        $modelOptions = optional($makeModel)->vehicleModels;
        $subModelOptions = optional($vehicleModelModel)->subModels;

        return response()->json([
            'year' => optional($year)->name,
            'make' => optional($makeModel)->id,
            'model' => optional($vehicleModelModel)->id,

            'makeOptions' => $makeOptions,
            'modelOptions' => $modelOptions,
            'subModelOptions' => $subModelOptions
        ]);
    }

    protected function getModelCases($modelVal) {
        $cases = [$modelVal];
        array_push($cases, str_replace('-', '', $modelVal));
        array_push($cases, str_replace('-', ' ', $modelVal));

        // Each word separately
        $explodeds = explode(' ', $modelVal);
        foreach ($explodeds as $exploded) {
            array_push($cases, $exploded);
        }

        // Combine First 2 Words
        if (count($explodeds) > 1) {
            array_push($cases, $explodeds[0] . ' ' . $explodeds[1]);
            array_push($cases, $explodeds[0] . '-' . $explodeds[1]);
            array_push($cases, $explodeds[0] . '' . $explodeds[1]);
        }

        // Separate Numbers And Letters
        $noWhitespacesModel = str_replace(' ', '', $modelVal);
        $numbers = preg_replace('/[^0-9]/', '', $noWhitespacesModel);
        $letters = preg_replace('/[^a-zA-Z]/', '', $noWhitespacesModel);

        if ($numbers && $letters) {
            array_push($cases, $numbers . $letters);
            array_push($cases, $numbers . '-' . $letters);
            array_push($cases, $numbers . ' ' . $letters);
            array_push($cases, $letters . $numbers);
            array_push($cases, $letters . '-' . $numbers);
            array_push($cases, $letters . ' ' . $numbers);
        }

        // &amp; case
        $asciiVersion = str_replace('&', '&amp;', $modelVal);
        array_push($cases, $asciiVersion);

        return $cases;
    }
}
