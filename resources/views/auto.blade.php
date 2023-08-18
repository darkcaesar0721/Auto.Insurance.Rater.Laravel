<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Insura Rater</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
</head>
<body class="bg-light">
    <div class="rater-container container">
        <a class="position-absolute mt-3 text-success" href="/customer/login">Know your quote number? Check here</a>
        <div class="pb-5 py-sm-5 text-center">
            <img class="d-block mx-auto mb-4" src="/images/insura-logo.png" alt="" width="125px" height="112px">
            <h2>Auto Insurance Rater</h2>
        </div>

        <div id="app">
            <rate-calculator></rate-calculator>
        </div>
    </div>

    {{--<footer class="text-muted text-center text-small">--}}
        {{--<p class="mb-1">© 2017-2018 Insura</p>--}}
        {{--<ul class="list-inline">--}}
            {{--<li class="list-inline-item"><a href="#">Privacy</a></li>--}}
            {{--<li class="list-inline-item"><a href="#">Terms</a></li>--}}
            {{--<li class="list-inline-item"><a href="#">Support</a></li>--}}
        {{--</ul>--}}
    {{--</footer>--}}

    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
