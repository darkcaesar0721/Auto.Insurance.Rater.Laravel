<?php

namespace App\Http\Controllers;

use App\Hasher;
use App\Mail\AutoMail;
use App\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller\CreateTransactionController;

class RaterController extends Controller
{
    public function index() {
        return view('auto');
    }

    public function redirectToAuto() {
        return redirect('/auto');
    }

    public function showQuote($hashId, Request $request) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $intPrice = (int) filter_var($quote->total_quoted_amount, FILTER_SANITIZE_NUMBER_INT);

        if ($intPrice == 0) {
            return view('404-price')->with('quote', $quote);
        }

        $oneTimePrice = $intPrice + 50;

        $competiors = [
            'stateFarm' => round($oneTimePrice * 1.29),
            'aaa' => round($oneTimePrice * 1.37),
            'allState' => round($oneTimePrice * 1.48)
        ];

        $insuraPrices = [
            'oneTime' => number_format((float)($intPrice + 50), 2, '.', ''),
            'monthlyDown' => number_format((float)($intPrice * 0.20 + 50), 2, '.', ''),
            'monthlyPrice' => number_format((float)($intPrice * 0.8/5 + 10), 2, '.', ''),
        ];

        $view = 'quotes.auto.pricing';
        $type = null;
        $data = [
            'quote' => $quote,
            'competitors' => $competiors,
            'insuraPrices' => $insuraPrices,
            'paymentType' => $type
        ];

        if ($request->type == 'one-time') {
            $view = 'quotes.auto.payment';
            $data['vehicles'] = $quote->vehicles;
            $type = "one-time";
            $data['paymentType'] = $type;
        } else if ($request->type == 'monthly') {
            $view = 'quotes.auto.payment';
            $data['vehicles'] = $quote->vehicles;
            $type = "monthly";
            $data['paymentType'] = $type;
        }

        return view($view)->with($data);
    }

    public function authorizeCreditCard($hashId, Request $request) {
        $request->validate([
            'full_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'payment_type' => 'required',
        ]);

        $quote = Quote::findOrFail(Hasher::decode($hashId));

        $quote->full_name = $request->full_name;
        $quote->phone = $request->phone_number;
        $quote->payment_type = $request->payment_type;
        $quote->save();

        if ($request->payment_type == 'agent-pay') {
            $request->validate([
                'agent_name' => 'required',
                'agent_number' => 'required',
            ]);

            $quote->agent_name = $request->agent_name;
            $quote->agent_no = $request->agent_number;
            $quote->email = $request->email;
            $quote->save();

            Mail::to($quote)->send(new AutoMail($quote));

            return redirect("/auto/quote/$hashId/authorized");
        }

        $request->validate([
            'full_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'card_no' => 'required',
            'expiration_month' => 'required',
            'expiration_year' => 'required',
            'ccv' => 'required',
        ]);

//        $explodedDate = explode('/', $request->expiry_date);
        $expiryDate = $request->expiration_year . '-' . $request->expiration_month;

        $intPrice = (int) filter_var($quote->total_quoted_amount, FILTER_SANITIZE_NUMBER_INT);
        $insuraPrices = [
            'oneTime' => number_format((float)($intPrice + 50), 2, '.', ''),
            'monthlyDown' => number_format((float)($intPrice * 0.20 + 50), 2, '.', ''),
        ];

        if ($request->payment_type === 'one-time') {
            $amount = $insuraPrices['oneTime'];
        } else if ($request->payment_type === 'monthly') {
            $amount = $insuraPrices['monthlyDown'];
        } else {
            return back()->withErrors(["Payment Type is malformed"]);
        }

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AuthorizeNetID'));
        $merchantAuthentication->setTransactionKey(env('AuthorizeNetKey'));

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber(str_replace('-', '', $request->card_no));
        $creditCard->setExpirationDate($expiryDate);
        $creditCard->setCardCode($request->ccv);

        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($quote->hash_id);
        $order->setDescription("INSURA Card Authorization");

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authOnlyTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setOrder($order);

        $data = new AnetAPI\CreateTransactionRequest();
        $data->setMerchantAuthentication($merchantAuthentication);
        $data->setRefId($quote->hash_id);
        $data->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new CreateTransactionController($data);
        if (env("APP_ENV") == "prod") {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        }

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                $quote->email = $request->email;
                $quote->card_authorized = true;
                $quote->save();

                Mail::to($quote)->send(new AutoMail($quote));

                return redirect("/auto/quote/$hashId/authorized");
            } else {
                $error = $response->getTransactionResponse()->getErrors()[0]->getErrorText();
                Log::warning($response->getTransactionResponse()->getErrors());
                return back()->withErrors([[$error]]);
            }
        } else {
            echo  "No response returned \n";
        }

        return $response;
    }


    public function authorized($hashId) {
        $quote = Quote::findOrFail(Hasher::decode($hashId));

        return view('quotes.auto.authorized')->with([
            'quote' => $quote,
            'authorized' => true
        ]);
    }
}
