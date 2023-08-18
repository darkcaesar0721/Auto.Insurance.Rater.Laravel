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

            <h3>Comprehensive & Collision</h3>
        </div>

        <div class="col-12">
            <h3 class="text-success">Comprehensive Deductible</h3>

            <p>
                Comprehensive coverage is optional. The deductible amount you see in your quote refers to the portion of the
                claim that you are responsible for. The higher the deductible, the lower the premium.
            </p>
            <p>
                This coverage pays expenses for damage to your vehicle not caused by collision that exceed the deductible that
                you select. You will be required to pay your deductible if your vehicle sustains damages that fall under this
                coverage. If your vehicle is totaled and the damages fall under Comprehensive Coverage, you will be
                reimbursed for the Actual Cash Value (ACV) of your vehicle, less your deductible. The actual cash value of
                your vehicle is its true market value. This is determined by the year, make, model and condition of your
                vehicle.
            </p>
            <br/>

            <h6 class="font-weight-bold">What is covered?</h6>
            <p>
                Comprehensive Coverage covers damages to your vehicle that are not the result of your vehicle overturning or
                colliding with another object or vehicle. This includes loss to your vehicle from fire, theft, vandalism, hail, or
                contact with an animal. This coverage will only apply to vehicles listed on your policy for which you have
                selected a Comprehensive Coverage deductible.
            </p>
            <br/>

            <h6 class="font-weight-bold">Example</h6>
            <p>
                Your car incurs hail damage during a storm. You will be required to pay the amount of your deductible, and
                the rest will be paid through Comprehensive Coverage. Your coverage limits, deductibles, and certain
                exclusions may apply. Please read your policy for details.
            </p>

            <h3 class="pt-4 text-success">Collision Deductible</h3>
            <p>
                Collision coverage is optional. The deductible amount you see in your quote refers to the portion of the claim
                that you are responsible for. The higher the deductible, the lower the premium.
            </p>
            <p>
                This coverage pays for damage to your vehicle caused by a collision that exceeds the deductible you select.
                You will be required to pay your deductible, and the remaining expenses will be paid by your insurance
                company, at their discretion. If your vehicle is totaled and the damages fall under Collision Coverage, you will
                be reimbursed for the Actual Cash Value (ACV) of your vehicle, less your deductible. The actual cash value of
                your vehicle is its true market value. This is determined by the year, make, model and condition of your
                vehicle.
            </p>

            <br/>

            <h6 class="font-weight-bold">What is covered?</h6>
            <p>
                Collision Coverage covers damage to your vehicle in the event it overturns or collides with another vehicle or
                object, other than an animal. This coverage will only apply to vehicles listed on your policy for which you
                have selected a Collision Coverage deductible.
            </p>
            <br/>

            <h6 class="font-weight-bold">Example</h6>
            <p>
                You are backing out of your driveway and hit a telephone pole. You will be required to pay the amount of your
                deductible, and the rest will be paid through Collision Coverage.
                <br/>
                Your coverage limits, deductibles, and certain exclusions may apply. Please read your policy for details.
            </p>

            <div class="col-12 col-sm-8 offset-sm-2 my-5">
                <iframe
                        width="560"
                        height="315"
                        src="https://embed.wix.com/video?instanceId=7812e227-8952-47fa-9599-313411d50079&biToken=1244454b-841e-0943-0638-2ccf890eaccf&pathToPage=auto-insurance-videos&channelId=aeff734344554ea79c7f3b4484dd423d&videoId=ff264af866174600a80f4edddba2e00a&compId=comp-jkiedqlg&sitePageId=goeft"
                        frameborder="0"
                        allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
</body>
</html>