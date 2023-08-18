<?php

namespace App\Http\Controllers;

use App\Hasher;
use App\Quote;
use Illuminate\Http\Request;

class DescriptionsController extends Controller
{
    public function bodilyInjury() {
        return view('descriptions.bi-pd');
    }

    public function collision() {
        return view('descriptions.collision');
    }

    public function vin() {
        return view('descriptions.vin');
    }
}
