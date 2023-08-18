<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Your Quote {{ $quote->hash_id }} | Insura Rater</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
    <link rel="stylesheet" type="text/css" href="/css/auto-payment.css">
</head>

<body class="bg-light">
<div class="container">
    <div class="pb-2 text-center col-12">
        <img class="mb-4" src="/images/insura-logo.png" alt="" width="125px" height="112px" draggable="false">
    </div>

    {{--<div id="app">--}}
    {{--<auto-competitors></auto-competitors>--}}
    {{--</div>--}}

    <div class="row mb-4">
        @include('quotes.auto.sidebar')

        <div class="col-sm-9">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="/js/auto-payment.js"></script>

<script>
    let error = '{!! $errors->first() !!}';

    $('document').ready(function() {
        if (error) {
            swal("Transaction Failed!", error, "error");
        }
    });
</script>
</body>
</html>