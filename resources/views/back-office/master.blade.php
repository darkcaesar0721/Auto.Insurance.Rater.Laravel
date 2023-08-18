<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Insura - Back Office</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    {{--<link rel="shortcut icon" href="assets/images/favicon.ico">--}}

    <link href="/css/back-office-app.css" rel="stylesheet" type="text/css" />
    <script src="/js/back-office/vendor/vendor.js"></script>
</head>

<body>
    <div id="wrapper">
        @include('back-office.partials.side-bar')

        <div class="content-page">
            <div class="content">
                @include('back-office.partials.nav-bar')

                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="/js/back-office/app.js"></script>
    <script src="/js/back-office/vendor/jquery.dataTables.js"></script>
    <script src="/js/back-office/vendor/dataTables.bootstrap4.js"></script>
    <script src="/js/back-office/vendor/dataTables.responsive.min.js"></script>
    <script src="/js/back-office/datatables.init.js"></script>
    <script src="/js/back-office-app.js"></script>
    <script src="/js/back-office/delete-record.js"></script>
</body>
</html>