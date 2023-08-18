<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Insura Rater</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
</head>

<body class="bg-light">
<div class="rater-container container my-auto">
    <div class="pb-5 text-center text-success">
        <a href="/auto">
            <img class="d-block mx-auto mb-4" src="/images/insura-logo.png" alt="" width="125px" height="112px">
        </a>
        <h3>Application Successful!</h3>
        <h4>We will contact you for further details.</h4>
        <br/>
        <h4>Your Reference No. - {{ $quote->hash_id }}</h4>
    </div>
</div>
</body>