<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Insura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
{{--<link rel="shortcut icon" href="assets/images/favicon.ico">--}}

    <link href="/css/back-office-app.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    {{--<script src="/back-office/js/vendor.js"></script>--}}
    {{--<script src="/back-office/js/app.js"></script>--}}
</body>
</html>