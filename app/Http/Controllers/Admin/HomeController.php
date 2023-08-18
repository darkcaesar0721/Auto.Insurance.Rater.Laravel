<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SubModel;
use App\VehicleModel;
use App\Year;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        return view('back-office.home');
    }
}
