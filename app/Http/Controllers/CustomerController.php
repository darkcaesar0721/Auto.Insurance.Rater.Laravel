<?php

namespace App\Http\Controllers;

use App\Hasher;
use App\Quote;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function showLogin() {
        return view('customers.login');
    }

    public function login(Request $request) {
        $request->validate([
            'quote_no' => 'required',
            'zip' => 'required'
        ]);
        $quote = Quote::where('id', Hasher::decode($request->quote_no))->where('zip', $request->zip)->first();

        if (!$quote) {
            return redirect()->back()->withErrors('Quote could not be found with given specifications')->withInput();
        }

        return redirect("/customer/quote/$quote->hash_id");
    }

    public function show($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        return view('customers.quote')->with([
            'quote' => $quote
        ]);
    }
}
