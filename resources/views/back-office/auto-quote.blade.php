@extends('back-office.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Quote</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="page-title">
                        Auto Quote - {{ $quote->hash_id }}
                        <a href="/admin/auto/{{ $quote->hash_id }}/edit" class="btn btn-lg btn-outline-success float-right">
                            <i class="fe-edit"></i>
                        </a>
                    </h4>
                    <br/>

                    <form method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-5">
                                <h4 class="header-title pb-2">Contact Details</h4>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Email</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="email" value="{{ $quote->email ?? '-' }}" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Phone</label>
                                    <div class="col">
                                        <input type="text" class="form-control phone-no" name="phone" value="{{ $quote->phone ?? '-' }}" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Address</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="address" value="{{ $quote->address }}" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">ZIP</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="zip" value="{{ $quote->zip }}" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Coverage</label>
                                    <div class="col">
                                        @if (!$isEditing)
                                        <input type="text" class="form-control" value="{{ ucfirst($quote->coverage) }}" readonly="true">
                                        @else
                                            <select class="form-control" name="coverage">
                                                <option value="basic" @if ($quote->coverage == 'basic') selected @endif>Basic</option>
                                                <option value="better" @if ($quote->coverage == 'better') selected @endif>Better</option>
                                                <option value="best" @if ($quote->coverage == 'best') selected @endif>Best</option>
                                            </select>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="col-5 offset-1">
                                <h4 class="header-title pb-2">Rate Details</h4>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Payment Type</label>
                                    <div class="col">
                                        @if (!$isEditing)
                                            <input type="text" class="form-control" value="{{ ucwords(str_replace('-', ' ', $quote->payment_type)) }}" readonly="true">
                                        @else
                                            <select class="form-control" id="payment-type-select" name="payment_type">
                                                <option value="monthly" @if ($quote->payment_type == 'monthly') selected @endif>Monthly</option>
                                                <option value="one-time" @if ($quote->payment_type == 'one-time') selected @endif>One-Time</option>
                                                <option value="agent-pay" @if ($quote->payment_type == 'agent-pay') selected @endif>Agent Pay</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Company</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="quote_company" value="{{ $quote->quote_company }}" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Quote</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="total_quoted_amount" value="{{ $quote->total_quoted_amount }}" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div id="agent-fields" class="{{ ($quote->payment_type !== 'agent-pay') ? 'd-none': '' }}">
                                    <h4 class="header-title pb-2">Agent Info</h4>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-4">Agent Name</label>
                                        <div class="col">
                                            <input type="text" class="form-control" name="agent_name" value="{{ $quote->agent_name }}" @if (!$isEditing) readonly="true" @endif>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-4">Agent No.</label>
                                        <div class="col">
                                            <input type="text" class="form-control agent-no-field" name="agent_no" value="{{ $quote->agent_no }}" @if (!$isEditing) readonly="true" @endif>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="header-title pb-2">Verifications</h4>
                                @if ($quote->payment_type != 'agent-pay')
                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-4">Payment Verified</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                <input type="text" class="form-control" value="{{ $quote->card_authorized ? 'YES' : 'NO' }}" readonly="true">
                                            @else
                                                <select class="form-control" name="card_authorized">
                                                    <option value="true" @if ($quote->card_authorized == 'monthly') selected @endif>YES</option>
                                                    <option value="false" @if ($quote->card_authorized == 'one-time') selected @endif>NO</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4">Email Verified</label>
                                    <div class="col">
                                        @if (!$isEditing)
                                            <input type="text" class="form-control" value="{{ $quote->email_verified ? 'YES' : 'NO' }}" readonly="true">
                                        @else
                                            <select class="form-control" name="email_verified">
                                                <option value="true" @if ($quote->email_verified == 'YES') selected @endif>YES</option>
                                                <option value="false" @if ($quote->email_verified == 'NO') selected @endif>NO</option>
                                            </select>
                                        @endif
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
                                            <input type="text" class="form-control"
                                                   name="drivers[{{ $driver->id }}][first_name]"
                                                   value="{{ $driver->first_name }}"
                                                   @if (!$isEditing) readonly="true" @endif
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Last Name</label>
                                        <div class="col">
                                            <input type="text" class="form-control"
                                                   name="drivers[{{ $driver->id }}][last_name]"
                                                   value="{{ $driver->last_name }}"
                                                   @if (!$isEditing) readonly="true" @endif
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Birth Date</label>
                                        <div class="col">
                                            <input type="text" class="form-control dob-field"
                                                   name="drivers[{{ $driver->id }}][dob]"
                                                   value="{{ $driver->dob }}"
                                                   @if (!$isEditing) readonly="true" @endif
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Good Driver</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                <input type="text" class="form-control" value="{{ $driver->good_driver ? "Yes" : "No" }}" @if (!$isEditing) readonly="true" @endif>
                                            @else
                                                <select class="form-control" name="drivers[{{ $driver->id }}][good_driver]">
                                                    <option value="true" @if ($driver->good_driver == 'Yes') selected @endif>Yes</option>
                                                    <option value="false" @if ($driver->good_driver == 'No') selected @endif>No</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Good Student Age</label>
                                        <div class="col">
                                            <input type="text" class="form-control"
                                                   name="drivers[{{ $driver->id }}][good_student_age]"
                                                   value="{{ $driver->good_student_age }}"
                                                   @if (!$isEditing) readonly="true" @endif
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">License No</label>
                                        <div class="col">
                                            <input type="text" class="form-control"
                                                   name="drivers[{{ $driver->id }}][license_no]"
                                                   value="{{ $driver->license_no }}"
                                                   @if (!$isEditing) readonly="true" @endif
                                            >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">License Status</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                <input type="text" class="form-control" value="{{ ucfirst($driver->license_status) }}" readonly="true">
                                            @else
                                                <select class="form-control" name="drivers[{{ $driver->id }}][license_status]">
                                                    <option value="valid" @if ($driver->license_status == 'valid') selected @endif>Valid</option>
                                                    <option value="expired" @if ($driver->license_status == 'expired') selected @endif>Expired</option>
                                                    <option value="valid_need_sr22" @if ($driver->license_status == 'valid_need_sr22') selected @endif>Valid Need SR-22</option>
                                                    <option value="suspended_sr22" @if ($driver->license_status == 'suspended_sr22') selected @endif>Suspended Need SR-22</option>
                                                    <option value="revoked" @if ($driver->license_status == 'revoked') selected @endif>Revoked</option>
                                                    <option value="none" @if ($driver->license_status == 'none') selected @endif>None</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Licensing Status</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                <input type="text" class="form-control" value="{{ ucfirst($driver->licensing_status) }}" readonly="true">
                                            @else
                                                <select class="form-control licensing-status" data-index="{{ $i }}" name="drivers[{{ $driver->id }}][licensing_status]">
                                                    <option value="california" @if ($driver->licensing_status == 'california') selected @endif>California Drivers License</option>
                                                    <option value="other_state" @if ($driver->licensing_status == 'other_state') selected @endif>Other State (USA Only)</option>
                                                    <option value="foreign" @if ($driver->licensing_status == 'foreign') selected @endif>Foreign License</option>
                                                    <option value="marticula_consular" @if ($driver->licensing_status == 'marticula_consular') selected @endif>Marticula Consular</option>
                                                    <option value="none" @if ($driver->licensing_status == 'none') selected @endif>None</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">State</label>
                                        <div class="col">
                                            <input type="text" class="form-control" value="{{ $driver->state ?? 'CA' }}" @if (!$isEditing) readonly="true" @endif>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">SR-22</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                <input type="text" class="form-control" value="{{ $driver->sr_22 ? 'Yes' : 'No' }}" readonly="true">
                                            @else
                                                <select class="form-control" name="drivers[{{ $driver->id }}][sr_22]">
                                                    <option value="true" @if ($driver->sr_22 == 'Yes') selected @endif>Yes</option>
                                                    <option value="false" @if ($driver->sr_22 == 'No') selected @endif>No</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Marital Status</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                <input type="text" class="form-control" value="{{ ucfirst($driver->marital_status) }}" readonly="true">
                                            @else
                                                <select class="form-control marital-status-select" data-index="{{$i}}" name="drivers[{{ $driver->id }}][marital_status]">
                                                    <option value="single" @if ($driver->marital_status == 'single') selected @endif>Single</option>
                                                    <option value="married" @if ($driver->marital_status == 'married') selected @endif>Married</option>
                                                </select>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="married-fields-block-{{$i}} {{ $driver->marital_status == 'single' ? 'd-none' : '' }}">
                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Spouse Is Driver</label>
                                            <div class="col">
                                                @if (!$isEditing)
                                                    <input type="text" class="form-control" value="{{ $driver->spouse_is_driver ? 'Yes' : 'No' }}" readonly="true">
                                                @else
                                                    <select class="form-control spouse-is-driver-select" data-index="{{$i}}" name="drivers[{{ $driver->id }}][spouse_is_driver]">
                                                        <option value="true" @if ($driver->spouse_is_driver == true) selected @endif>Yes</option>
                                                        <option value="false" @if ($driver->spouse_is_driver == false) selected @endif>No</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Wife Birth Date</label>
                                            <div class="col">
                                                <input type="text" class="form-control dob-field" name="drivers[{{ $driver->id }}][wife_dob]" value="{{ $driver->wife_dob }}" @if (!$isEditing) readonly="true" @endif>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-form-label col-sm-3">Wife First Name</label>
                                            <div class="col">
                                                <input type="text" class="form-control" name="drivers[{{ $driver->id }}][wife_first_name]" value="{{ $driver->wife_first_name }}" @if (!$isEditing) readonly="true" @endif>
                                            </div>
                                        </div>

                                        <div class="spouse-is-driver-block-{{$i}} {{ $driver->spouse_is_driver ? 'd-none' : '' }}">
                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Wife License Status</label>
                                                <div class="col">
                                                    @if (!$isEditing)
                                                        <input type="text" class="form-control" value="{{ ucfirst($driver->wife_license_status) }}" readonly="true">
                                                    @else
                                                        <select class="form-control" name="drivers[{{ $driver->id }}][wife_license_status]">
                                                            <option value="valid" @if ($driver->wife_license_status == 'valid') selected @endif>Valid</option>
                                                            <option value="expired" @if ($driver->wife_license_status == 'expired') selected @endif>Expired</option>
                                                            <option value="valid_need_sr22" @if ($driver->wife_license_status == 'valid_need_sr22') selected @endif>Valid Need SR-22</option>
                                                            <option value="suspended_sr22" @if ($driver->wife_license_status == 'suspended_sr22') selected @endif>Suspended Need SR-22</option>
                                                            <option value="revoked" @if ($driver->wife_license_status == 'revoked') selected @endif>Revoked</option>
                                                            <option value="none" @if ($driver->wife_license_status == 'none') selected @endif>None</option>
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Wife Licensing Status</label>
                                                <div class="col">
                                                    <input type="text" class="form-control"
                                                           name="drivers[{{ $driver->id }}][wife_licensing_status]"
                                                           value="{{ $driver->wife_licensing_status }}"
                                                           @if (!$isEditing) readonly="true" @endif>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-form-label col-sm-3">Wife SR-22</label>
                                                <div class="col">
                                                    @if (!$isEditing)
                                                        <input type="text" class="form-control" value="{{ $driver->wife_sr_22 ? "Yes" : "No"}}" readonly="true">
                                                    @else
                                                        <select class="form-control" name="drivers[{{ $driver->id }}][wife_sr_22]">
                                                            <option value="true" @if ($driver->wife_sr_22 == 'Yes') selected @endif>Yes</option>
                                                            <option value="false" @if ($driver->wife_sr_22 == 'No') selected @endif>No</option>
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                            <input type="text" class="form-control" name="vehicles[{{ $vehicle->id }}][vin_no]" value="{{ $vehicle->vin_no }}" @if (!$isEditing) readonly="true" @endif>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Year</label>
                                        <div class="col">
                                            <input type="text" class="form-control" name="vehicles[{{ $vehicle->id }}][year]" value="{{ $vehicle->year }}" @if (!$isEditing) readonly="true" @endif>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Make</label>
                                        <div class="col">
                                            <input type="text" class="form-control" name="vehicles[{{ $vehicle->id }}][make]" value="{{ $vehicle->make }}" @if (!$isEditing) readonly="true" @endif>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Model</label>
                                        <div class="col">
                                            <input type="text" class="form-control" name="vehicles[{{ $vehicle->id }}][model]" value="{{ $vehicle->model }}" @if (!$isEditing) readonly="true" @endif>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Sub-Model</label>
                                        <div class="col">
                                            <input type="text" class="form-control" name="vehicles[{{ $vehicle->id }}][sub_model]" value="{{ $vehicle->sub_model }}" @if (!$isEditing) readonly="true" @endif>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Coverage</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                @php $vehicleLiability = $vehicle->coverage === 'none' ? 'Full Liability' : '$' . $vehicle->coverage @endphp

                                                <input type="text" class="form-control" value="{{ $vehicle->coverage ? $vehicleLiability : '-' }}" @if (!$isEditing) readonly="true" @endif>
                                            @else
                                                <select class="form-control" name="vehicles[{{ $vehicle->id }}][coverage]">
                                                    <option value="none" @if ($vehicle->coverage == 'none') selected @endif>Full Liability</option>
                                                    <option value="250" @if ($vehicle->coverage == '250') selected @endif>$250</option>
                                                    <option value="500" @if ($vehicle->coverage == '500') selected @endif>$500</option>
                                                    <option value="1000" @if ($vehicle->coverage == '1000') selected @endif>$1000</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Usage</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                <input type="text" class="form-control" value="{{ ucfirst($vehicle->usage) }}" @if (!$isEditing) readonly="true" @endif>
                                            @else
                                                <select class="form-control" name="vehicles[{{ $vehicle->id }}][usage]">
                                                    <option value="commute" @if ($vehicle->usage == 'commute') selected @endif>Commute</option>
                                                    <option value="pleasure" @if ($vehicle->usage == 'pleasure') selected @endif>Pleasure</option>
                                                    <option value="business" @if ($vehicle->usage == 'business') selected @endif>Business</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-sm-3">Alarm</label>
                                        <div class="col">
                                            @if (!$isEditing)
                                                <input type="text" class="form-control" value="{{ ucfirst($vehicle->alarm) }}" @if (!$isEditing) readonly="true" @endif>
                                            @else
                                                <select class="form-control" name="vehicles[{{ $vehicle->id }}][alarm]">
                                                    <option value="none" @if ($vehicle->alarm == 'none') selected @endif>None</option>
                                                    <option value="passive" @if ($vehicle->alarm == 'passive') selected @endif>Passive</option>
                                                    <option value="active" @if ($vehicle->alarm == 'active') selected @endif>Active</option>
                                                    <option value="tracking_device" @if ($vehicle->alarm == 'tracking_device') selected @endif>Tracking Device</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($i+1 < $quote->vehicles->count())
                                        <hr class="py-2"/>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <hr/>
                                    <label class="col-form-label col-sm-3">Notes</label>
                                    <textarea class="form-control" id="note-textarea" name="note" rows="5">{{ $quote->note }}</textarea>
                                <hr/>
                            </div>
                        </div>

                        @if ($isEditing)
                            <div class="col-4 mx-auto">
                                <button class="btn btn-success btn-block" type="submit">Save</button>
                            </div>
                        @endif
                    </form>

                    @if ($quote->files_count > 0)
                    <div class="row">
                        <div class="col-12">
                            <h4>File Uploads</h4>
                        </div>
                        @foreach ($quote->files as $dirName => $dir)
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body ribbon-box">
                                        <h4 class="ribbon ribbon-info">{{ $dirName }}</h4>
                                        @foreach ($dir as $i => $file)
                                            <a href="/admin/{{ $file }}" target="_blank" class="my-2">
                                                <img src="/admin/{{ $file }}" width="150px">
                                            </a>
                                            <a href="/admin/{{ $file }}" download class="ml-3" style="font-size: 22px;">
                                                <i class="fe-download mx-auto"></i>
                                            </a>
                                            <br/>

                                            @if (count($dir)-1 > $i)
                                                <hr/>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="/js/back-office/edit-quote.js"></script>
@endsection