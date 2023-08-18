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
</head>

<body>

<div id="wrapper">
    <div class="content-page ml-0">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Auto Quote - {{ $quote->hash_id }}</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="page-title">Auto Quote - {{ $quote->hash_id }}</h4>
                                <br/>

                                <div class="row">
                                    <div class="col-5">
                                        <h4 class="header-title pb-2">Contact Details</h4>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Email</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->email ?? '-' }}" readonly="true">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Phone</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->phone ?? '-' }}" readonly="true">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Address</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->address ?? '-' }}" readonly="true">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">ZIP</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->zip ?? '-' }}" readonly="true">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Coverage</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->coverage }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-5 offset-1">
                                        <h4 class="header-title pb-2">Rate Details</h4>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Payment Type</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->payment_type ?? '-' }}" readonly="true">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Company</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->quote_company ?? '-' }}" readonly="true">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Quote</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->total_quoted_amount ?? '-' }}" readonly="true">
                                            </div>
                                        </div>

                                        @if ($quote->payment_type == 'agent-pay')
                                            <h4 class="header-title pb-2">Agent Info</h4>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4">Agent Name</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $quote->agent_name }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4">Agent No.</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $quote->agent_no }}" readonly="true">
                                                </div>
                                            </div>
                                        @endif

                                        <h4 class="header-title pb-2">Verifications</h4>
                                        @if ($quote->payment_type != 'agent-pay')
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-4">Payment Verified</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $quote->card_authorized ? 'YES' : 'NO' }}" readonly="true">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-4">Email Verified</label>
                                            <div class="col">
                                                <input type="text" class="form-control" value="{{ $quote->email_verified ? 'YES' : 'NO' }}" readonly="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-5">
                                        <h4 class="header-title pb-2 text-underline"><u>Drivers</u></h4>
                                        @foreach ($quote->drivers as $i => $driver)
                                            <h4 class="header-title pb-2">Driver - {{ $i+1 }}</h4>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">First Name</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->first_name ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Last Name</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->last_name ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Birth Date</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->dob ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Good Driver</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->good_driver ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Good Student Age</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->good_student_age ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">License No</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->license_no ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">License Status</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ ucfirst($driver->license_status) ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Licensing Status</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->licensing_status ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">State</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->state ?? 'CA' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">SR-22</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $driver->sr_22 ? 'Yes' : 'No' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Marital Status</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ ucfirst($driver->marital_status) ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            @if ($driver->marital_status == 'married')
                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-3">Spouse Is Driver</label>
                                                    <div class="col">
                                                        <input type="text" class="form-control" value="{{ $driver->spouse_is_driver ? 'Yes' : 'No' }}" readonly="true">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-3">Wife Birth Date</label>
                                                    <div class="col">
                                                        <input type="text" class="form-control" value="{{ $driver->wife_dob ?? '-' }}" readonly="true">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-form-label col-sm-3">Wife First Name</label>
                                                    <div class="col">
                                                        <input type="text" class="form-control" value="{{ $driver->wife_first_name ?? '-' }}" readonly="true">
                                                    </div>
                                                </div>

                                                @if ($driver->spouse_is_driver)
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-sm-3">Wife License Status</label>
                                                        <div class="col">
                                                            <input type="text" class="form-control" value="{{ $driver->wife_license_status ?? '-' }}" readonly="true">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-form-label col-sm-3">Wife First Name</label>
                                                        <div class="col">
                                                            <input type="text" class="form-control" value="{{ $driver->wife_licensing_status ?? '-' }}" readonly="true">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-form-label col-sm-3">Wife SR-22</label>
                                                        <div class="col">
                                                            <input type="text" class="form-control" value="{{ $driver->wife_sr_22 ?? '-' }}" readonly="true">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            @if ($i+1 < $quote->drivers->count())
                                                <hr class="py-2"/>
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="col-5 offset-1">
                                        <h4 class="header-title pb-2 text-underline"><u>Vehicles</u></h4>

                                        @foreach ($quote->vehicles as $i => $vehicle)
                                            <h4 class="header-title pb-2">Vehicle - {{ $i+1 }}</h4>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">VIN</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $vehicle->vin_no ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Year</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $vehicle->year ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Make</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $vehicle->make ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Model</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $vehicle->model ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Sub-Model</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ $vehicle->sub_model ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Coverage</label>
                                                <div class="col">
                                                    @php $vehicleLiability = $vehicle->coverage === 'none' ? 'Full Liability' : '$' . $vehicle->coverage @endphp

                                                    <input type="text" class="form-control" value="{{ $vehicle->coverage ? $vehicleLiability : '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Usage</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ ucfirst($vehicle->usage) ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Alarm</label>
                                                <div class="col">
                                                    <input type="text" class="form-control" value="{{ ucfirst($vehicle->alarm) ?? '-' }}" readonly="true">
                                                </div>
                                            </div>

                                            @if ($i+1 < $quote->vehicles->count())
                                                <hr class="py-2"/>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                {{--@if ($quote->files_count > 0)--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-12">--}}
                                            {{--<h4>File Uploads</h4>--}}
                                        {{--</div>--}}
                                        {{--@foreach ($quote->files as $dirName => $dir)--}}
                                            {{--<div class="col-lg-4">--}}
                                                {{--<div class="card">--}}
                                                    {{--<div class="card-body ribbon-box">--}}
                                                        {{--<h4 class="ribbon ribbon-info">{{ $dirName }}</h4>--}}
                                                        {{--@foreach ($dir as $i => $file)--}}
                                                            {{--<a href="/admin/{{ $file }}" target="_blank" class="my-2">--}}
                                                                {{--<img src="/admin/{{ $file }}" width="150px">--}}
                                                            {{--</a>--}}
                                                            {{--<a href="/admin/{{ $file }}" download class="ml-3" style="font-size: 22px;">--}}
                                                                {{--<i class="fe-download mx-auto"></i>--}}
                                                            {{--</a>--}}
                                                            {{--<br/>--}}

                                                            {{--@if (count($dir)-1 > $i)--}}
                                                                {{--<hr/>--}}
                                                            {{--@endif--}}
                                                        {{--@endforeach--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--@endforeach--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
</body>
</html>