<div class="col-sm-3 px-0 h-100" style="background: #f1f1f1; border:2px solid #96a6b6">
    <div class="col-12 text-center pt-3 pb-2" style="color: #0b8b49;">
        <h2><strong>Your Quote</strong></h2>
    </div>

    <div class="col-12 py-3 text-center" style="background-color: #96a6b6; color: white">
        <i class="fa fa-file-text-o fa-2x"></i> <span class="ml-2" style="font-size: 22px;">Quote Details</span>
    </div>

    <div class="col-12 py-3">
        <h5 class="text-muted">Quote Number</h5>
        <h3>{{ $quote->hash_id }}</h3>
    </div>

    <hr class="my-0"/>

    <a href="?type=one-time" style="text-decoration: none">
        <div class="col-12 py-3 pointer text-success {{ $paymentType != 'one-time' ? 'text-muted' : ''}}" style="background-color: white">
            <h5 class="font-weight-bold">PAID IN FULL
                @if ($paymentType == 'one-time')
                    <br/><span class="font-italic">(Selected)</span>
                @endif
            </h5>
            <h3>${{ $insuraPrices['oneTime'] }}</h3>
        </div>
    </a>

    <hr class="my-0"/>

    <a href="?type=monthly" style="text-decoration: none">
        <div class="col-12 py-3 pointer text-success {{ $paymentType != 'monthly' ? 'text-muted' : ''}}" style="background-color: white">
            <h5 class="font-weight-bold">MONTHLY PAYMENT
                @if ($paymentType == 'monthly')
                    <br/><span class="font-italic">(Selected)</span>
                @endif
            </h5>
            <h3>${{ $insuraPrices['monthlyPrice'] }}</h3>
        </div>
    </a>

    <div class="row bg-dark text-center mx-0">
        <div class="col-12 p-0" style="border: 5px solid #16bc66">
            <h3 class="text-white py-2 m-0">Help Center</h3>
        </div>
        <div class="col-12 p-0" style="border: 5px solid #16bc66; border-top: none">
            <h5 class="text-white text-left px-3 pt-3 pb-2">Need Help?</h5>
            <h5 class="text-white py-0">Call 800-720-0313</h5>
            <h5 class="text-white py-0">Live Chat</h5>
            <h5 class="text-white py-0">Email</h5>
            <h5 class="text-white pt-0 pb-2">Text</h5>
        </div>
    </div>

    {{--<div class="row mx-0">--}}
    {{--<div class="col-12 h_iframe px-0">--}}
    {{--<iframe src="https://embed.wix.com/video?instanceId=7812e227-8952-47fa-9599-313411d50079&biToken=1244454b-841e-0943-0638-2ccf890eaccf&pathToPage=auto-insurance-videos&channelId=aeff734344554ea79c7f3b4484dd423d&videoId=978e181559e34cbd927e353915592538&compId=comp-jkiedqlg&sitePageId=goeft" frameborder="0" allowfullscreen></iframe>--}}
    {{--</div>--}}
    {{--</div>--}}
</div>