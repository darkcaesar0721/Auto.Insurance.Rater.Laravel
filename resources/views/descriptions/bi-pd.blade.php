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
            <h3>Liability Bodily Injury - Property Damage</h3>
        </div>

        <div class="col-12">
            <p>
                Liability Bodily Injury - Property Damage coverage is a mandatory coverage in most states. The limits shown within this help
                text are only examples and may or may not be applicable to your state; the values here are simply used to assist in defining how
                the coverage is displayed and what each value means.
            </p>
            <br/>

            <h6 class="font-weight-bold">Coverage Limits?</h6>
            <p>
                Coverage limits are displayed in 3 numbers - $25,000/$50,000/$25,000. The first number ($25,000) is the amount of Bodily
                Injury coverage per person. The second number ($50,000) is the amount of Bodily Injury coverage per accident for all persons
                combined. The third number ($25,000) is the amount of property damage coverage per accident.
            </p>
            <br/>

            <h6 class="font-weight-bold">How much coverage do I need?</h6>
            <p>
                The rule of thumb is to carry enough coverage to protect your assets. If the amount of damages exceeds your Liability Limits, you
                will be responsible for the amount of damages above your limits.
            </p>

            <br/>

            <h6 class="font-weight-bold">Who and What is covered?</h6>
            <p>
                You are covered for any bodily injury and property damage that you cause in an accident to another individual or their property.
                Damage to property also includes damage that you cause to public property such as buildings, telephone poles and fences.
            </p>
            <br/>

            <h6 class="font-weight-bold">What does it pay?</h6>
            <p>
                In an accident where it has been determined that you are at fault, Bodily Injury and Property Damage Coverage will cover
                expenses for medical costs, lost wages, sickness, death, and property damage related to the accident. This coverage also pays for
                pain and suffering that may result from the accident. It is considered to be the liability coverage portion of your policy.
            </p>
            <br/>

            <h6 class="font-weight-bold">Example</h6>
            <p>
                You cause a motor vehicle accident and the other driver is injured. Their vehicle has also sustained damage. You have a limit of
                $25,000 per person/$50,000 per accident/$25,000 property damage for Bodily Injury and Property Damage Coverage. You will
                have up to $25,000 in coverage for the other driver's injuries. If there were passengers in his/her vehicle who were also injured,
                you would be covered for up to $50,000 for the accident. You will also be covered for up to $25,000 of property damage. This
                amount will only apply to any of the damages that fall under Bodily Injury and Property Damage Coverage if it is determined that
                you are liable.
            </p>

            <p>
                Your coverage limits, deductibles, and certain exclusions may apply. Please read your policy for details.
            </p>

            <div class="col-12 col-sm-8 offset-sm-2 my-5">
                <iframe
                        width="560"
                        height="315"
                        src="https://embed.wix.com/video?instanceId=7812e227-8952-47fa-9599-313411d50079&biToken=1244454b-841e-0943-0638-2ccf890eaccf&pathToPage=auto-insurance-videos&channelId=aeff734344554ea79c7f3b4484dd423d&videoId=30b2ad0ba87b4a4a8694f9f48ee637c0&compId=comp-jkiedqlg&sitePageId=goeft"
                        frameborder="0"
                        allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
</body>
</html>