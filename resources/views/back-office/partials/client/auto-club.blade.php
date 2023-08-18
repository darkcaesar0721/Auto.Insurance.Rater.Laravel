<form id="auto-club-tab" class="policy-row" method="post" action="/admin/clients/save-auto-club">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-3">
                    <label class="col-form-label">Auto Club Membership #</label>
                    <input 
                        type="text" 
                        class="form-control capitalize" 
                        name="member_id" 
                        value="{{ 
                            old('member_id') ? old('member_id') : 
                            ($client && $client->autoClub ? $client->autoClub->member_id : '')
                        }}" 
                        placeholder="Auto Club Membership #"
                    >
                    @if ($errors->has('member_id'))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('member_id') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-3">
                    <label class="col-form-label">Payment Method *</label>
                    <select
                        class="form-control payment-method-dropdown"
                        name="payment_method_id"
                    >
                         @foreach ($paymentMethods as $pm)
                            <option
                                value="{{ $pm->id }}"
                                @if(old('payment_method_id') == 'direct cash' || ($client && $client->autoClub && $client->autoClub->payment_method_id == $pm->id)) selected @endif>{{ $pm->name }}</option>
                         @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 referral-source-fields">
                    <label class="col-form-label">Referral Source *</label>
                    <div class="input-group">
                        <select class="form-control" name="referral_source_id">
                            @foreach ($referralSource as $rs)
                                <option
                                    placeholder="Referral Source"
                                    value="{{ $rs->id }}"
                                    @if(old('referral_source_id') == $rs->id || $client->autoClub && $client->autoClub->referral_source_id == $rs->id) selected  @endif
                                >{{ $rs->referral_company }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 referral-fee-container">
                    <label class="col-form-label">Referral Amount *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-referral-fee">$</span>
                        </div>
                        <input 
                            type="text"
                            class="form-control amount" 
                            name="referral_amount" 
                            value="{{ 
                                old('referral_amount') ? old('referral_amount') : 
                                ($client && $client->autoClub ? $client->autoClub->referral_amount : '')
                            }}" 
                            placeholder="Amount"
                            aria-describedby="basic-addon-referral-fee"
                            readonly
                        >
                        @if ($errors->has('referral_amount'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('referral_amount') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 check-no-field">
                    <label class="col-form-label">Check No.</label>
                    <input
                        type="text"
                        class="form-control capitalize"
                        name="check_no"
                        value="{{ old('check_no') ? old('check_no') : ($client->autoClub ? $client->autoClub->check_no : '') }}"
                        placeholder="Check No."
                    >
                </div>
            </div>
            <div class="form-group row">
                <div class="col col-4 term-field">
                    <label class="col-form-label">Term *</label>
                    <select
                        class="form-control term auto-club-term"
                        name="term"
                    >
                        <option 
                            value="year"
                            @if(old('term') == 'year' || ($client && $client->autoClub && $client->autoClub->term == 'year')) selected @endif
                        >Year</option>
                        <option 
                            value="6_months"
                            @if(old('term') == '6_months' || ($client && $client->autoClub && $client->autoClub->term == '6_months')) selected @endif
                        >6 Months</option>
                        <option 
                            value="3_months"
                            @if(old('term') == '3_months' || ($client && $client->autoClub && $client->autoClub->term == '3_months')) selected @endif
                        >3 Months</option>
                        <option
                            value="monthly"
                            @if(old('term') == 'monthly' || ($client && $client->autoClub && $client->autoClub->term == 'monthly')) selected @endif
                        >Monthly</option>
                    </select>
                </div>
                <div class="col col-4">
                    <label class="col-form-label">Effective Date *</label>
                    <input
                        class="form-control datepicker text-main-color effective_date" 
                        name="effective_date" 
                        value="{{ old('effective_date') ? old('effective_date') : ($client && $client->autoClub ? $client->autoClub->effective_date : date('m/d/Y')) }}"
                        placeholder="Effective Date"
                    >
                </div>
                <div class="col col-4 expiration-date-field">
                    <label class="col-form-label">Expiration Date *</label>
                    <input
                        class="form-control text-main-color expiration_date"
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
                <div class="col-md-4">
                    <label class="col-form-label">Premium *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input
                            type="text" 
                            class="form-control capitalize premium" 
                            name="premium" 
                            value="{{ 
                                old('premium') ? old('premium') : 
                                ($client && $client->autoClub ? $client->autoClub->premium : '')
                            }}" 
                            placeholder="Premium"
                            aria-describedby="basic-addon-premium"
                            readonly
                        >
                        @if ($errors->has('premium'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('premium') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Co. Fees *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input
                            type="text" 
                            class="form-control co_fees" 
                            name="co_fees" 
                            value="{{ 
                                old('co_fees') ? old('co_fees') : 
                                ($client && $client->autoClub ? $client->autoClub->co_fees : '')
                            }}" 
                            placeholder="Co. Fees"
                            aria-describedby="basic-addon-co-fees"
                            readonly
                        >
                        @if ($errors->has('co_fees'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('co_fees') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Company Down Payment *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-company-down-payment">$</span>
                        </div>
                        <input 
                            type="text"
                            class="form-control company_down_payment" 
                            name="down_payment" 
                            value="{{ 
                                old('down_payment') ? old('down_payment') : 
                                ($client && $client->autoClub ? $client->autoClub->down_payment : '')
                            }}" 
                            placeholder="Company Down Payment"
                            aria-describedby="basic-addon-company-down-payment"
                            readonly
                        >
                        @if ($errors->has('down_payment'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('down_payment') }}
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
                    <label class="col-form-label">Payments</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-monthly-payment">$</span>
                        </div>
                        <input 
                            type="text"
                            class="form-control monthly_payment" 
                            name="monthly_payment" 
                            value="{{ 
                                old('monthly_payment') ? old('monthly_payment') : 
                                ($client && $client->autoClub ? $client->autoClub->monthly_payment : '')
                            }}" 
                            placeholder="Monthly Payment"
                            aria-describedby="basic-addon-monthly-payment"
                            readonly
                        >
                        @if ($errors->has('monthly_payment'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('monthly_payment') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="col-form-label">Company Total *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-company-total">$</span>
                        </div>
                        <input
                            type="text"
                            class="form-control company-total"
                            name="company_total"
                            value="{{ 
                                old('company_total') ? old('company_total') : 
                                ($client && $client->autoClub ? $client->autoClub->company_total : '')
                            }}"
                            placeholder="Company total"
                            readonly
                            aria-describedby="basic-addon-company-total"
                        >
                        @if ($errors->has('company_total'))
                            <div class="float-left alert alert-danger col-md-12">
                                {{ $errors->first('company_total') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-2">
                    @if ($client)
                        @if($client->autoClub && $client->autoClub->CSV && !$client->autoClub->CSV->terminated_at)
                        Uploaded at {{$client->autoClub->CSV->created_at}}
                        @elseif($client->autoClub && $client->autoClub->CSV && $client->autoClub->CSV->terminated_at)
                        Terminated at {{$client->autoClub->CSV->terminated_at}}
                        @endif
                        <br>
                        <a href="/admin/clients/generateCSV/{{ $client->id }}">
                            <i class="mdi mdi-file-delimited"></i>
                            Upload CSV
                        </a>
                    @endif
                </div>
                <div class="col-md-2">
                    @if ($client && $client->autoClub && $client->autoClub->CSV && !$client->autoClub->CSV->terminated_at)
                    <br>
                    <br>
                        <a href="/admin/clients/generateCSV/{{ $client->id }}?terminate=1">
                            <i class="mdi mdi-file-delimited"></i>
                            Terminate CSV
                        </a>
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
                            @for ($i = 0; $i < count($formNamesAutoClub); $i++)
                                <tr>
                                    <td><label>{{ $formNamesAutoClub[$i][0] }}</label></td>
                                    <td>
                                        <div>
                                            @if ($client)
                                                <a href="/admin/clients/generate-form/{{ $client->id }}/{{ $formNamesAutoClub[$i][1] }}" target="_blank">
                                                    <i class="mdi mdi-file-pdf"></i>
                                                    Generate
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($formNamesAutoClub[$i][2]) 
                                        {{ $formNamesAutoClub[$i][2]->updated_at }}
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