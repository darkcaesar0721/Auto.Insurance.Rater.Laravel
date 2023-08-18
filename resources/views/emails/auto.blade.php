<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Your Quote - {{ $quote->hash_id }}</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .container {
            max-width: 960px;
            background-color: white;
            margin: 0 auto;
        }

        .bg-light {
            text-align: center;
            background-color: #f8f9fa !important;
            margin: 0;
            padding-top: 15px
        }

        h5 {
            font-size: 1.25rem;
        }

        .text-success {
            color: #28a745 !important;
        }

        .font-weight-bold {
            font-weight: 700;
        }

        .font-italic {
            font-style: italic;
        }

        .text-left {
            text-align: left;
        }

        .btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
            padding: 10px 25px;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 0.3rem;
            text-decoration: none;
        }

        .vertical-align-mid {
            vertical-align: middle
        }

        .pr-5 {
            padding-right: 5px
        }

        .upload-btn-block {
            margin: 10px 0 35px 0
        }

        @media only screen and (max-width: 435px) {
            .upload-btn-block {
                margin: 50px 0 35px 0
            }

            .upload-btn-block > a {
                font-size: 1em
            }
        }

    </style>
</head>

<body class="bg-light">
    <div class="container bg-white py-2 mt-4" style="padding: 30px 50px">
        <div class="col-12 text-center my-3" style="text-align: center; width: 100%">
            <img src="{{ env("APP_URL") }}/images/insura-logo@2.png">
        </div>

        <div class="mt-4">
            <h5 class="text-success">Congratulations your policy is almost issued!</h5>
        </div>

        <div class="mt-4">
            <h5 class="text-success">Application Number - {{ $quote->hash_id }}</h5>
        </div>

        <div class="col-12 mt-4 text-left">
            <span class="font-weight-bold">Applicant Name:</span> <br/>
            <span>{{ $quote->full_name }}</span><br/>
            <span>{{ $quote->address }}</span> <br/><br/>

            <span class="font-weight-bold pt-2">Requested Coverage:</span> <br/>
            <span>
                @switch ($quote->coverage)
                    @case('basic')
                        $15,000/$30,000/$5,000
                        @break;
                    @case('better')
                        $25,000/$50,000/$25,000
                        @break;
                    @case('best')
                        $100,000/$300,000/$50,000
                        @break;
                @endswitch
            </span><br/>
            <small class="font-italic">*Other coverage may be available – for additional coverage call Service Center <strong>800-720-0313</strong>.</small><br/>
        </div>

        <div class="col-12 mt-5 text-center">
            <p>
                We are pleased you have chosen to be insured with our company, to insure accurate rating for our customers, we require a few
                items prior to issuing your policy. Rest assured that no fee has been charged to your credit card and in the event that pricing on your application changes you will be
                notified prior to completing any transaction. Documents you provide will help prompt and proper processing of your application to avoid any future changes in your pricing.
            </p>

            <h5>Here is what’s required!</h5>
        </div>

        <div>
            <div style="height: 50px;">
                <img class="pr-5 vertical-align-mid" src="{{ env("APP_URL") }}/images/fa-id-card.png" style="height: 35px">
                <span class="vertical-align-mid">Driver’s license for All Driver<sup>*</sup> </span>
            </div>
            <div class="col-12 my-3" style="height: 40px;">
                <img class="pr-5 vertical-align-mid" src="{{ env("APP_URL") }}/images/fa-file-o.png" style="height: 27px">
                <span class="vertical-align-mid">Copy of your Vehicles Registration<sup>**</sup> </span>
            </div>
            <div class="col-12 my-3" style="height: 50px;">
                <img class="pr-5 vertical-align-mid" src="{{ env("APP_URL") }}/images/fa-car.png" style="height: 35px">
                <span class="vertical-align-mid">Photos of Vehicle required for Comprehensive / Collision to your policy<sup>***</sup> </span>
            </div>
        </div>

        <div class="upload-btn-block">
            <a href="{{ env("APP_URL") }}/auto/quote/{{ $quote->hash_id }}/upload" class="btn-success" style="color: white">Upload Documents Here</a>
        </div>

        <div class="col-12 font-italic mt-5">
            <ul>
                <li>
                    <small>
                        Copy of a valid California driver’s license, or out of state license. If you are not licensed in the USA please provide a governmental issued drivers license from issuing country. If you
                        are applying as a Matricula Consular, please send copy. If your license is expired or cancelled or revoked please provide a Government issued ID card.
                    </small>
                </li>
                <li>
                    <small>
                        Copies of California issued registration for all listed cars must also be attached, if the vehicle is new please providing Sales contract. If you only have a title or a bill of sale you
                        may provide any document showing the vehicle is being registered in California with in the next 30 days.
                    </small>
                </li>
                <li>
                    <small>
                        Please Provide 2 photos of each car showing all sides if you have selected comprehensive and collision coverage. If your car is being Lease and financed please make sure to
                        indicted in your response with photos so that we may have your bank of financial institution listed on the policy
                    </small>
                </li>
            </ul>
        </div>

        <div class="col-12 text-center my-2">
            <small class="font-italic">If the button doesn't work, copy this url in your address bar:<br/> <a href="{{ env("APP_URL") }}/auto/quote/{{ $quote->hash_id }}/upload">{{ env("APP_URL") }}/auto/quote/{{ $quote->hash_id }}/upload</a></small>
        </div>
    </div>
</body>