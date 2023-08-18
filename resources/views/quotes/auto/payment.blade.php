@extends('quotes.master')

@section('content')

<div class="col-12" style="border:2px solid #16b35b">
    <div class="col-12 my-4 text-center">
        <i class="fa fa-shopping-cart fa-2x align-middle mr-3"></i>
        <h2 class="align-middle d-inline">Checkout</h2>
    </div>

    <div class="col-12 py-2 px-3" style="background-color: #f1f1f1">
        <i class="fa fa-user fa-2x align-middle mr-3"></i>
        <h4 class="align-middle d-inline">Your Liability Coverage</h4>
    </div>

    <div class="col-12 py-2 px-3 d-inline-flex justify-content-between">
        <div class="col-auto px-sm-5">
            <p class="font-weight-bold my-1">Coverage Highlights</p>
            <p class="my-1">Bodily Injury Limits</p>
            <p class="my-1">Property Damage Limit</p>
        </div>

        <div class="col-auto px-sm-5 text-right">
            <p class="font-weight-bold my-1">Limits</p>
            @switch($quote->coverage)
                @case('basic')
                    <p class="my-1">$15k/$30k</p>
                    <p class="my-1">$5k</p>
                    @break
                @case('better')
                    <p class="my-1">$25k/$50k</p>
                    <p class="my-1">$25k</p>
                    @break
                @case('best')
                    <p class="my-1">$100k/$300k</p>
                    <p class="my-1">$50k</p>
                    @break
            @endswitch
        </div>
    </div>

    @foreach ($vehicles as $vehicle)
        <div class="col-12 py-2 px-3" style="background-color: #f1f1f1">
            <i class="fa fa-car fa-2x align-middle mr-3"></i>
            <h4 class="align-middle d-inline">Coverage For Your <span class="font-weight-bold">{{ $vehicle->year }} {{ $vehicle->make }}</span></h4>
        </div>

        <div class="col-12 py-2 px-3 d-sm-inline-flex justify-content-between">
            <div class="col-12 col-sm-auto px-sm-5">
                <p class="font-weight-bold my-1">Coverage Highlights</p>
                <p class="my-1">Comprehensive Coverage & Deductibles</p>
                <p class="my-1">Collision Coverage & Deductibles</p>
            </div>

            <div class="col-12 col-sm-auto px-sm-5 text-sm-right">
                <p class="font-weight-bold my-1">Deductibles/Limits</p>
                @if ($vehicle->coverage == 'none')
                    <p class="my-1">None</p>
                    <p class="my-1">None</p>
                @else
                    <p class="my-1">${{ $vehicle->coverage }}</p>
                    <p class="my-1">${{ $vehicle->coverage }}</p>
                @endif
            </div>
        </div>
    @endforeach

    <div class="col-12 p-2 mb-3" style="border: 1px solid black">
        <p class="text-justify m-0 font-italic">
            <small>
                To provide you with an accurate premium, we order driving record and claims history information during our verification process. We
                also order prior insurance information, when available. Insura Insurance obtains this information from an independent third-party data
                provider whose contact information is available to you on our site.
            </small>
        </p>
    </div>

    <div class="col-12">
        <hr/>
    </div>

    <div class="col-12">
        <div class="row">
            <div class="col-12 col-sm-6 order-1 order-sm-0">
                <form id="payment_form" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input payment-type-radio" type="radio" name="payment_type" id="monthly" value="monthly" @if ($paymentType == 'monthly') checked @endif>
                            <label class="custom-control-label" for="monthly">
                                Amount Due Today: ${{ $insuraPrices['monthlyDown'] }}
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input payment-type-radio" type="radio" name="payment_type" id="one-time" value="one-time" @if ($paymentType == 'one-time') checked @endif>
                            <label class="custom-control-label" for="one-time">
                                Pay In Full: ${{ $insuraPrices['oneTime'] }}
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input payment-type-radio" type="radio" name="payment_type" id="agent-pay" value="agent-pay">
                            <label class="custom-control-label" for="agent-pay">
                                Agent Pay: Require No Payment
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email') ? old('email') : $quote->email }}">
                        <small class="form-text text-muted">We need your email address to deliver you the Welcome Package. We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" class="form-control" name="full_name" placeholder="Full Name" value="{{ old('full_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control phone_us" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">
                    </div>

                    <div class="form-group agent-info" style="display: none">
                        <label for="agent_name">Agent Name</label>
                        <input type="text" class="form-control" name="agent_name"  value="{{ old('agent_name') }}">
                    </div>
                    <div class="form-group agent-info" style="display: none">
                        <label for="agent_number">Agent Number</label>
                        <input type="text" class="form-control agent_no" name="agent_number" placeholder="111-111-1111" value="{{ old('agent_number') }}">
                    </div>

                    <div class="form-group cc-info">
                        <label for="card_no">Card Number</label>
                        <input type="text" class="form-control card_no" id="card_no" name="card_no" placeholder="1234-1234-1234-1234" @if (env('APP_ENV') !== 'prod') value="4111111111111111" @endif>
                    </div>
                    <div class="row cc-info">
                        <div class="col-12">
                            <label for="expiry_date">Expiration Date</label><br/>
                            <div class="form-group cc-info d-inline-flex">
                                <select class="form-control" name="expiration_month" style="width: 100px">
                                    <option>Month</option>
                                    @for ($i = 1; $i < 13; $i++)
                                        <option value="{{ strlen($i) == 1 ? '0' . $i : $i}}">
                                            {{ strlen($i) == 1 ? '0' . $i : $i}}
                                        </option>
                                    @endfor
                                </select>
                                <select class="form-control" name="expiration_year">
                                    <option>Year</option>
                                    @for ($i = 18; $i < 37; $i++)
                                        <option value="20{{$i}}">
                                            20{{$i}}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group cc-info">
                        <label for="ccv">Security Code (CCV)</label>
                        <input type="text" class="form-control" id="ccv" name="ccv" maxlength="6" placeholder="CCV" @if (env('APP_ENV') !== 'prod') value="123" @endif>
                    </div>
                </form>
            </div>

            <div class="col-12 col-sm-6 text-center order-0 order-sm-1">
                <img class="w-75" src="/images/authorize-net.png" draggable="false">
            </div>
        </div>

        <div class="col-4 mx-auto text-center">
            <button type="button" id="submit_btn" class="btn btn-success btn-block btn-lg">SUBMIT</button>
        </div>

        <p class="mt-4">
            <small>
                Insurance coverage is an individual and personal decision, and you need to use your own judgment in deciding what
                coverages and monetary limits to select, as Insura Insurance Agency cannot anticipate what level of protection you are
                comfortable with or require protecting yourself from financial exposure. The information we provide is of a general nature
                based on the information you submit, and is not a recommendation of specific coverage levels for you to purchase. You
                should not rely on us to determine what coverages are best for you, and Insura Insurance agency assumes no
                responsibility for making sure you have adequate coverage for your unique situation. The coverage options displayed may
                not present all coverages, limits, or deductibles that could be available to you. Actual coverages vary by state and are
                subject to individual eligibility. Other terms, conditions and exclusions apply. The estimate you see above will not be
                complete if specific documents are needed to complete the transaction. To provide actual quoted premium, we need to
                verify your driving record. You'll be able to see your final quote before you finish buying your policy. California Department
                of Insurance License #0L92605
            </small>
        </p>
    </div>
</div>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/597f1b25dc0d70602312c868/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->

@endsection