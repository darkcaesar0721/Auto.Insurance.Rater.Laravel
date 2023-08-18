<form id="license-only-tab" class="policy-row" method="post" action="/admin/clients/save-license-only" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">License No #</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">MWL</span>
                        </div>
                        <input
                            type="text" 
                            class="form-control capitalize" 
                            name="license_number" 
                            value="{{ 
                                old('license_number') ?? ($client && $client->licenseOnly ? str_replace('MWL', '', $client->licenseOnly->license_number) : '')
                            }}"
                        >
                    </div>
                    
                    @if ($errors->has('license_number'))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('license_number') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Payment Method *</label>
                    <select
                        class="form-control payment-method-dropdown"
                        name="payment_method_id"
                    >
                         @foreach ($paymentMethods as $pm)
                            <option
                                value="{{ $pm->id }}"
                                @if(old('payment_method_id') == 'direct cash' || ($client && $client->licenseOnly && $client->licenseOnly->payment_method_id == $pm->id)) selected @endif>{{ $pm->name }}</option>
                         @endforeach
                    </select>
                </div>

                <div class="col-md-4 referral-source-fields">
                    <label class="col-form-label">Referral Source *</label>
                    <div class="input-group">
                        <select class="form-control" name="referral_source_id">
                            @foreach ($referralSource as $rs)
                                <option
                                    placeholder="Referral Source"
                                    value="{{ $rs->id }}"
                                    @if(old('referral_source_id') == $rs->id || $client->licenseOnly && $client->licenseOnly->referral_source_id == $rs->id) selected  @endif
                                >{{ $rs->referral_company }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col col-4 term-field">
                    <label class="col-form-label">Term *</label>
                    <select
                        class="form-control license-term"
                        name="term"
                    >
                        <option 
                            value="10_years"
                            @if(old('term') == '10_years' || ($client && $client->licenseOnly && $client->licenseOnly->term == '10_years')) selected @endif
                        >10 Years</option>
                        <option 
                            value="5_years"
                            @if(old('term') == '5_years' || ($client && $client->licenseOnly && $client->licenseOnly->term == '5_years')) selected @endif
                        >5 Years</option>
                        <option 
                            value="3_years"
                            @if(old('term') == '3_years' || ($client && $client->licenseOnly && $client->licenseOnly->term == '3_years')) selected @endif
                        >3 Years</option>
                        <option
                            value="year"
                            @if(old('term') == 'year' || ($client && $client->licenseOnly && $client->licenseOnly->term == 'year')) selected @endif
                        >Year</option>
                    </select>
                </div>
                <div class="col col-4">
                    <label class="col-form-label">Effective Date *</label>
                    <input
                        class="form-control datepicker-license text-main-color effective_date_license" 
                        name="effective_date" 
                        value="{{ old('effective_date') ? old('effective_date') : ($client && $client->licenseOnly ? $client->licenseOnly->effective_date : date('m/d/Y')) }}"
                        placeholder="Effective Date"
                    >
                </div>
                <div class="col col-4 expiration-date-field">
                    <label class="col-form-label">Expiration Date *</label>
                    <input
                        class="form-control text-main-color expiration_date_license"
                        name="expiration_date"
                        placeholder="Expiration Date"
                        readonly
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4 license-price-values">
                    <label class="col-form-label">Price</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input
                            id="price-license"
                            type="text" 
                            class="form-control capitalize license-price" 
                            name="price" 
                            value="{{ 
                                old('price') ? old('price') : 
                                ($client && $client->licenseOnly ? $client->licenseOnly->price : '')
                            }}" 
                            placeholder="Price"
                        >
                        @if ($errors->has('price'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('price') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 license-price-values">
                    <label class="col-form-label">Ship Fee</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input
                            id="ship-fee-license"
                            type="text" 
                            class="form-control" 
                            name="ship_fee" 
                            value="{{ 
                                old('ship_fee') ? old('ship_fee') : 
                                ($client && $client->licenseOnly ? $client->licenseOnly->ship_fee : '')
                            }}" 
                            placeholder="Ship Fee"                            
                        >
                        @if ($errors->has('ship_fee'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('ship_fee') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Total cost</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input
                            id="total-cost-license" 
                            type="text"
                            class="form-control" 
                            name="total_cost" 
                            value="{{ 
                                old('total_cost') ? old('total_cost') : 
                                ($client && $client->licenseOnly ? $client->licenseOnly->total_cost : '')
                            }}" 
                            placeholder="Total cost"
                        >
                        @if ($errors->has('total_cost'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('total_cost') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">Tracking No.</label>
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            name="tracking_no"
                            value="{{ 
                                old('tracking_no') ? old('tracking_no') : 
                                ($client && $client->licenseOnly ? $client->licenseOnly->tracking_no : '')
                            }}"
                            placeholder="Tracking No."
                        >
                        @if ($errors->has('tracking_no'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('tracking_no') }}
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label class="col-form-label">Photo</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="photo">
                                <label class="custom-file-label" for="inputGroupFile01">{{$client->licenseOnly->photo ?? "Choose file"}}</label>
                        </div>
                    </div>
                    <p><small> The type must be jpg or png, and the size should not exceed 5MB</small></p>
                    @if ($errors->has('photo'))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('photo') }}
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <label class="col-form-label">Signature</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="sign">
                                <label class="custom-file-label" for="inputGroupFile01">{{$client->licenseOnly->sign ?? "Choose file"}}</label>
                        </div>
                    </div>
                    <p><small> The type must be jpg or png, and the size should not exceed 5MB </small></p>
                    @if ($errors->has('sign'))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('sign') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <label class="col-form-label col col-md-12">Forms</label>
                
                <div class="col col-md-10">
                    <table class="table hover">
                        <thead>
                            <th>Form Name</th>
                            <th>Link</th>
                            <th>Last Update</th>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 1; $i++)
                                <tr>
                                    <td><label>{{ $formLicense[$i-1] }}</label></td>
                                    <td>
                                        <div>
                                            @if ($client)
                                                <a href="/admin/clients/generate-form/{{ $client->id }}/{{ 'LICENSE-' .$i }}" target="_blank">
                                                    <i class="mdi mdi-file-pdf"></i>
                                                    Generate
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if (count($licensePdfs)) 
                                            {{ $licensePdfs[0]->updated_at }}
                                        @else
                                            Not Downloaded
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-9">
        </div>
        <div class="col-sm-3">
            <button class="btn btn-success btn-block" type="submit">Save</button>
        </div>
    </div>
    <input type="hidden" name="client_id" value="{{ $client ? $client->id : 0 }}">
</form>

