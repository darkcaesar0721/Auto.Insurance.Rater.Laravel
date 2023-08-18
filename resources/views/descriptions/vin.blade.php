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
    <div class="rater-container container">
        <div class="pb-5 text-center">
            <a href="/auto">
                <img class="d-block mx-auto mb-4" src="/images/insura-logo.png" alt="" width="125px" height="112px">
            </a>

            <h3>What’s a Vehicle Identification Number (VIN)</h3>
        </div>

        <div class="col-12">
            <p>
                A vehicle identification numbers (VIN) is a unique code given to each on-road vehicle in the
                United States. Since 1981, each new car has been given a standardized 17-digit code, which
                includes a serial number.
            </p>
            <p>
                Older cars may have VINs too, although they will not follow the standardized formula. A VIN lets
                you unlock vital information about the vehicle and its history.
            </p>
            <br/>

            <h6 class="font-weight-bold">Locating a VIN</h6>
            <p>
                You can find a VIN on the car itself and on a variety of documents.
            </p>
            <br/>

            <h6 class="font-weight-bold">Finding it on the Car</h6>
            <p>
                The two most common places are the dashboard and driver’s side door jamb sticker.
                Other places to find it are on the engine and inside the hood.
            </p>

            <h3 class="pt-4 text-success">Finding it on Paperwork</h3>
            <p>
                The VIN is always on vehicle title documents. It’s also on insurance policies, service
                records and police reports for the vehicle.
            </p>

            <br/>
            <div class="col-12 text-center">
                <img src="/images/vin-img1.png" />
            </div>
            <br/>
            <br/>

            <div class="col-12 text-center">
                <img src="/images/vin-img2.jpg" />
            </div>
        </div>
    </div>
</body>
</html>