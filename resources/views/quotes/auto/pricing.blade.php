@extends('quotes.master')

@section('content')

<div class="col-12 px-5">
    <p style="text-align: justify;">
        The <strong class="font-italic">initial quote includes</strong> your selected coverages and at least the minimum state financial responsibility requirements.
        Additionally, financed or leased vehicles may require Comprehensive and Collision coverages.
        Feel free to call us if you need to customize your quote 800-720-0313. This quote is based on current information provided by the user and may change after final underwriting.
    </p>
</div>
<div class="col-12">
    <div class="card-deck mb-3 text-center">
        <div class="card mb-4 shadow-sm">
            <div class="ribbon green">
                <span>Save 17%</span>
            </div>
            <div class="card-header bg-white">
                <img src="/images/insura-logo@2.png" class="w-25" />
                <img src="/images/am-best.png" class="w-50" />
                <hr/>
                <h3 class="card-title pricing-card-title font-weight-bold">PAID IN FULL</h3>
                <h5 class="card-title pricing-card-title font-weight-bold">NO DOWN PAYMENT REQUIRED</h5>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title pb-2">${{ $insuraPrices['oneTime'] }}</h1>

                <a href="?type=one-time">
                    <button type="button" class="btn btn-lg btn-outline-success">Buy Now</button>
                </a>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white">
                <img src="/images/insura-logo@2.png" class="w-25" />
                <img src="/images/am-best.png" class="w-50" />
                <hr/>
                <h3 class="card-title pricing-card-title font-weight-bold">MONTHLY PAYMENT</h3>
                <h5 class="card-title pricing-card-title font-weight-bold">DOWN PAYMENT REQUIRED</h5>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title pb-2">${{ $insuraPrices['monthlyPrice'] }}<small class="text-muted">/mo</small></h1>

                <a href="?type=monthly">
                    <button type="button" class="btn btn-lg btn-outline-success">Buy Now</button>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <h4 class="font-italic font-weight-bold">Save hundreds a year over the competitor pricing!</h4>
    <div class="card-deck text-center">
        <div class="card mb-4 mb-sm-0 shadow-sm">
            <div class="ribbon red"><span>29% More</span></div>
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Company</h4>
            </div>
            <div class="card-body">
                <img src="/images/third-party-logos/statefarm.svg" height="60px" />
                <hr/>
                <h2 class="card-title pricing-card-title">${{ $competitors['stateFarm'] }}</h2>
            </div>
        </div>
        <div class="card mb-4 mb-sm-0 shadow-sm">
            <div class="ribbon red"><span>37% More</span></div>
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Company</h4>
            </div>
            <div class="card-body">
                <img src="/images/third-party-logos/aaa.svg" height="60px" />
                <hr/>
                <h2 class="card-title pricing-card-title">${{ $competitors['aaa'] }}</h2>
            </div>
        </div>
        <div class="card mb-4 mb-sm-0 shadow-sm">
            <div class="ribbon red"><span>48% More</span></div>
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Company</h4>
            </div>
            <div class="card-body">
                <img src="/images/third-party-logos/all-state.svg" height="60px" />
                <hr/>
                <h2 class="card-title pricing-card-title">${{ $competitors['allState'] }}</h2>
            </div>
        </div>
    </div>
</div>

@endsection