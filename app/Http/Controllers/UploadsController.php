<?php

namespace App\Http\Controllers;

use App\Hasher;
use App\Quote;
use Illuminate\Http\Request;

class UploadsController extends Controller
{
    public function showAuto($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $quote->email_verified = true;
        $quote->save();

        return view('uploads.auto')->with('quote', $quote);
    }

    public function showAutoSuccess($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        return view('uploads.auto-success')->with('quote', $quote);
    }
}
