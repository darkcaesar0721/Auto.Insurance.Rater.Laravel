<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">

    <title>Your Quote - {{ $quote->hash_id }}</title>

    <style>
        .container {
            max-width: 960px;
        }

        .text-success {
            color: #28a745!important
        }
    </style>
</head>

<body class="bg-light">

    <div class="container bg-white py-2 mt-4">
            <div class="col-12 text-center my-3">
            <img src="/images/insura-logo@2.png">
        </div>

        <div class="col-12 text-center mt-4">
            <h6>
                QUESTIONS CALL 800-720-0313 <br/>
                LIVE CHAT
            </h6>
        </div>

        <div class="col-12 mt-4">
            <span class="font-weight-bold">Application: <span class="font-weight-bold">{{ $quote->hash_id }}</span></span> <br/>
            <span>{{ $quote->full_name }}</span><br/>
            <span>{{ $quote->address }}</span> <br/><br/>
        </div>

        <div class="col-12 text-center font-italic mb-5">
            <h6>
                Missing items could delays issuance of policy.
            </h6>
        </div>


        <div id="app">
            <auto-uploader :quote-id="{{ $quote->hash_id }}" :drivers-count="{{ $quote->drivers->count() }}" :vehicles-count="{{ $quote->vehicles->count() }}" inline-template>
                <div>
                    <div class="col-12 my-3 text-center">
                        <i class="fa fa-id-card fa-2x align-middle mr-2 text-success"></i>
                        <span class="align-middle">Driver’s license for All Driver<sup>*</sup> </span>
                    </div>

                    <div class="col-12 col-sm-10 mx-auto">
                        <small>
                            Copy of a valid California driver’s license, or out of state license. If you are not licensed in the USA please provide a governmental issued
                            drivers license from issuing country. If you are applying as a Matricula Consular, please send copy. If your license is expired or cancelled
                            or revoked please provide a Government issued ID card.
                        </small>
                    </div>

                    <div class="col-12 col-sm-8 mx-auto mt-3 mb-5">
                        <drivers-dropzone ref="driversComponent" :quote-id="quoteId"></drivers-dropzone>
                    </div>

                    <div class="col-12 my-3 text-center">
                        <i class="fa fa-file-o fa-2x align-middle mr-2 text-success"></i>
                        <span class="align-middle">Copy of your Vehicles Registration<sup>*</sup> </span>
                    </div>


                    <div class="col-12 col-sm-10 mx-auto">
                        <small>
                            Copies of California issued registration for all listed cars must also be attached, if the vehicle is new please
                            providing Sales contract. If you only have a title or a bill of sale you may provide any document showing the
                            vehicle is being registered in California with in the next 30 days.
                        </small>
                    </div>

                    <div class="col-12 col-sm-8 mx-auto mt-3 mb-5">
                        <vehicles-registration-dropzone ref="vehiclesRegistrationComponent" :quote-id="quoteId"></vehicles-registration-dropzone>
                    </div>

                    <div class="col-12 my-3 text-center">
                        <i class="fa fa-file-o fa-2x align-middle mr-2 text-success"></i>
                        <span class="align-middle">Photos of Vehicle required for Comprehensive / Collision to your policy<sup>*</sup> </span>
                    </div>


                    <div class="col-12 col-sm-10 mx-auto">
                        <small>
                            Please Provide 2 photos of each car showing all sides if you have selected comprehensive and collision
                            coverage. If your car is being Lease and financed please make sure to indicted in your response with
                            photos so that we may have your bank of financial institution listed on the policy.
                        </small>
                    </div>

                    <div class="col-12 col-sm-8 mx-auto mt-3 mb-5">
                        <vehicle-photos-dropzone ref="vehiclesPhotosComponent" :quote-id="quoteId"></vehicle-photos-dropzone>
                    </div>

                    <div class="col-12 col-sm-4 mx-auto text-center">
                        <button class="btn btn-success btn-lg btn-block" @click="submitBtnClicked">Submit Files</button>
                    </div>
                </div>
            </auto-uploader>
        </div>
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>
</body>