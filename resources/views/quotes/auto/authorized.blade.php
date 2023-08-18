<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Authorized {{ $quote->hash_id }}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
</head>

<body class="bg-light">
<div class="rater-container container">
    <div class="pb-4 text-center">
        <a href="/auto">
            <img class="d-block mx-auto mb-4" src="/images/insura-logo.png" alt="" width="125px" height="112px">
        </a>
        <h3 class="text-success">Congratulations! You’re done!</h3>
        <h3 class="text-success mt-4">Application # {{ $quote->hash_id }}</h3>
        <h5 class="text-success mt-4">Check your Email – Or Call 800-720-0313</h5>
    </div>

    <div class="col-12 font-weight-bold text-success">
        <p class="mb-4 text-center">Your ID cards and policy documents are on their way to your email!</p>
        <p class="mb-4">
            Your credit card will not be charged until we have completed all our underwriting on submitted information –
            once our underwriter has gone through your attached documents and verified your email and contact
            information - and addition to all information provided or attached.
        </p>
        <p class="mb-4">
            You should not rely on us to determine what coverages are best for you, and Insura Insurance Agency assumes
            no responsibility for making sure you have adequate coverage for your unique situation. The coverage options
            displayed may not present all coverages, limits, or deductibles that could be available to you. Actual coverages
            vary by state and are subject to individual eligibility. Other terms, conditions and exclusions apply.
        </p>
        <p class="mb-4">
            The estimate you see above will not be complete if specific documents are needed to complete the transaction.
            To provide actual quoted premium, we need to verify your driving record. You'll be able to see your final quote
            before you any amount is charged to your credit card or policy activated. Insura Insurance agency is a license
            agent of California Department of Insurance License #0L92605
        </p>

        <p class="mb-2">
            Still Have Questions?
        </p>

        <p class="mb-2 text-center">
            INSURA INSURANCE AGENCY <br/>
            info@4insura.com <br/>
            TOLL FREE: 800-720-0313 <br/>
            TOLL FREE FAX 800-318-3854 <br/>
            LOCATIONS SEE MAP <br/>
            Service Center Hours <br/>
            M-F <br/>
            9am-7pm PST <br/>
            Sat <br/>
            10-am -4pm PST <br/>
            Sunday <br/>
            Closed
        </p>

    </div>
</div>
</body>
</html>