<div class="policy-row">
    <div class="row">
        <div class="col-12">
            <h2>
                <span class="policy-title">Policy</span> No. {{ $policyIndex + 1 }}
            </h2>

            <div class="row {{ $endorsementAvailable ? '' : 'd-none' }}">
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label class="col-form-label">
                                <input
                                    class="is-endorsement" 
                                    type="checkbox"
                                    name="is_endorsement[{{$policyIndex}}]"
                                    value="1"
                                    @if(old('is_endorsement.' . $policyIndex) || ($endorsementAvailable && $policy && $policy->is_endorsement)) checked @endif
                                > Endorsement
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col col-md-6">
                    <label class="col-form-label">Company * <a href="#" target="_blank" class="policy-website-link"><i class="mdi mdi-web"></i></a></label>
                    <select class="form-control policy-company-list" name="company_list_id[]">
                        @foreach ($companies as $company)
                            <option 
                                value="{{ $company->id }}" 
                                @if(old('company_list_id.' . $policyIndex) == $company->id || ($policy && $policy->company_list_id == $company->id)) selected 
                                @elseif(!old('company_list_id.' . $policyIndex) && !$policy && $company->name == 'Allstate') selected
                                @endif
                            >{{ $company->company_name }}
                            </option>

                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="col-form-label">Policy Number *</label>
                    <input 
                        type="text" 
                        class="form-control capitalize" 
                        name="policy_number[]" 
                        value="{{ old('policy_number.' . $policyIndex) ? old('policy_number.' . $policyIndex) : ($policy ? $policy->policy_number : '') }}" 
                        placeholder="Policy Number"
                    >
                    @if ($errors->has('policy_number.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('policy_number.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <div class="col col-4 term-field">
                    <label class="col-form-label">Term *</label>
                    <select
                        class="form-control term"
                        name="term[]"
                    >
                        <option 
                            value="year"
                            @if(old('term.' . $policyIndex) == 'year' || ($policy && $policy->term == 'year')) selected @endif
                        >Year</option>
                        <option 
                            value="6_months"
                            @if(old('term.' . $policyIndex) == '6_months' || ($policy && $policy->term == '6_months')) selected @endif
                        >6 Months</option>
                        <option 
                            value="3_months"
                            @if(old('term.' . $policyIndex) == '3_months' || ($policy && $policy->term == '3_months')) selected @endif
                        >3 Months</option>
                        <option
                            value="monthly"
                            @if(old('term.' . $policyIndex) == 'monthly' || ($policy && $policy->term == 'monthly')) selected @endif
                        >Monthly</option>
                    </select>
                </div>
                <div class="col col-4">
                    <label class="col-form-label">Effective Date *</label>
                    <input
                        class="form-control datepicker text-main-color effective_date" 
                        name="effective_date[]" 
                        value="{{ old('effective_date.' . $policyIndex) ? old('effective_date.' . $policyIndex) : ($policy && $policy->effective_date ? $policy->effective_date : date('m/d/Y')) }}"
                        placeholder="Effective Date"
                        
                    >
                    @if ($errors->has('effective_date.' . $policyIndex))
                        <div class="alert alert-danger">
                            {{ $errors->first('effective_date.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
                <div class="col col-4 expiration-date-field">
                    <label class="col-form-label">Expiration Date *</label>
                    <input
                        class="form-control text-main-color expiration_date"
                        name="expiration_date[]"
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
                            name="premium[]" 
                            value="{{ old('premium.' . $policyIndex) ? old('premium.' . $policyIndex) : ($policy ? $policy->premium : '') }}" 
                            placeholder="Premium"
                            aria-describedby="basic-addon-premium"
                        >
                    </div>
                    @if ($errors->has('premium.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('premium.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Payment Method *</label>
                    <select
                        class="form-control payment-method-dropdown"
                        name="paymentm_method_option[]"
                    >
                        <option
                            value="direct cash"
                            @if(old('paymentm_method_option.' . $policyIndex) == 'direct cash' || ($policy && $policy->paymentm_method_option == 'direct cash')) selected @endif
                        >Direct Cash</option>
                        <option
                            value="direct credit card"
                            @if(old('paymentm_method_option.' . $policyIndex) == 'direct credit card' || ($policy && $policy->paymentm_method_option == 'direct credit card')) selected @endif
                        >Direct Credit Card</option>
                        <option
                            value="direct other"
                            @if(old('paymentm_method_option.' . $policyIndex) == 'direct other' || ($policy && $policy->paymentm_method_option == 'direct other')) selected @endif
                        >Direct Other</option>
                        <option
                            value="referral pay"
                            @if(old('paymentm_method_option.' . $policyIndex) == 'referral pay' || ($policy && $policy->paymentm_method_option == 'referral pay')) selected @endif
                        >Referral Pay</option>
                        <option
                            value="referral-customer pay"
                            @if(is_null($policy) || old('paymentm_method_option.' . $policyIndex) == 'referral-customer pay' || ($policy && $policy->paymentm_method_option == 'referral-customer pay')) selected @endif
                        >Referral-Customer Pay</option>
                    </select>
                </div>
                <div class="col-md-4 referral-fee-fields">
                    <label class="col-form-label">Referral Fee *</label>
                    <select
                        class="form-control referral-fee-dropdown"
                        name="referral_fee_option[]"
                    >
                        <option
                            value="no"
                            @if(old('referral_fee_option.' . $policyIndex) == 'no' || ($policy && $policy->referral_fee_option == 'no')) selected @endif
                        >No</option>
                        <option
                            value="yes"
                            @if(is_null($policy) || old('referral_fee_option.' . $policyIndex) == 'yes' || ($policy && $policy->referral_fee_option == 'yes')) selected @endif
                        >Yes</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">Co. Fees *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input
                            type="text" 
                            class="form-control co_fees" 
                            name="co_fees[]" 
                            value="{{ old('co_fees.' . $policyIndex) ? old('co_fees.' . $policyIndex) : ($policy ? $policy->co_fees : '') }}" 
                            placeholder="Co. Fees"
                            aria-describedby="basic-addon-co-fees"
                        >
                    </div>
                    @if ($errors->has('co_fees.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('co_fees.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Broker Fee *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input
                            type="text" 
                            class="form-control broker_fee" 
                            name="broker_fee[]" 
                            value="{{ old('broker_fee.' . $policyIndex) ? old('broker_fee.' . $policyIndex) : ($policy ? $policy->broker_fee : '') }}" 
                            placeholder="Broker Fee"
                            aria-describedby="basic-addon-broker-fee"
                        >
                    </div>
                    @if ($errors->has('broker_fee.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('broker_fee.' . $policyIndex) }}
                        </div>
                    @endif
                </div>

                <div class="col-md-4 referral-source-fields">
                    <label class="col-form-label">Referral Source *</label>
                    <div class="input-group">
                        <select class="form-control policy-referral-source" name="policy_referral_source[]">
                            @foreach ($referralSource as $rs)
                                <option
                                    placeholder="Referral Source"
                                    value="{{ $rs->id }}"
                                    @if(old('policy_referral_source.' . $policyIndex) == $rs->id || ($policy && $policy->referral_source_id == $rs->id)) selected
                                    @endif
                                >{{ $rs->referral_company }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">Company Down Payment *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-company-down-payment">$</span>
                        </div>
                        <input 
                            type="text"
                            class="form-control company_down_payment" 
                            name="company_down_payment[]" 
                            value="{{ old('company_down_payment.' . $policyIndex) ? old('company_down_payment.' . $policyIndex) : ($policy ? $policy->company_down_payment : '') }}" 
                            placeholder="Company Down Payment"
                            aria-describedby="basic-addon-company-down-payment"
                        >
                    </div>
                    @if ($errors->has('company_down_payment.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('company_down_payment.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Total Down Payment *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-total-down-payment">$</span>
                        </div>
                        <input
                            type="text" 
                            class="form-control total-down-payment" 
                            name="total_down_payment[]" 
                            value="{{ old('total_down_payment.' . $policyIndex) ? old('total_down_payment.' . $policyIndex) : ($policy ? $policy->total_down_payment : '') }}" 
                            placeholder="Total Down Payment"
                            readonly
                            aria-describedby="basic-addon-total-down-payment"
                        >
                    </div>
                    @if ($errors->has('total_down_payment.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('total_down_payment.' . $policyIndex) }}
                        </div>
                    @endif
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
                            name="amount[]" 
                            value="{{ old('amount.' . $policyIndex) ? old('amount.' . $policyIndex) : ($policy ? $policy->amount : '') }}" 
                            placeholder="Amount"
                            aria-describedby="basic-addon-referral-fee"
                        >
                    </div>
                    @if ($errors->has('amount.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('amount.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">Monthly Payment *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-monthly-payment">$</span>
                        </div>
                        <input 
                            type="text"
                            class="form-control monthly_payment" 
                            name="monthly_payment[]" 
                            value="{{ old('monthly_payment.' . $policyIndex) ? old('monthly_payment.' . $policyIndex) : ($policy ? $policy->monthly_payment : '') }}" 
                            placeholder="Monthly Payment"
                            aria-describedby="basic-addon-monthly-payment"
                        >
                    </div>
                    @if ($errors->has('monthly_payment.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('monthly_payment.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="col-form-label">Agency Total *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-agency-total">$</span>
                        </div>
                        <input
                            type="text" 
                            class="form-control agency-total"
                            name="agency_total[]"
                            value="{{ old('agency_total.' . $policyIndex) ? old('agency_total.' . $policyIndex) : ($policy ? $policy->agency_total : '') }}"
                            placeholder="Agency Total"
                            readonly
                            aria-describedby="basic-addon-agency-total"
                        >
                    </div>
                    @if ($errors->has('agency_total.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('agency_total.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
                <div class="col-md-4 check-no-field">
                    <label class="col-form-label">Check No.</label>
                    <input
                        type="text"
                        class="form-control capitalize"
                        name="check_no[]"
                        value="{{ old('check_no.' . $policyIndex) ? old('check_no.' . $policyIndex) : ($policy ? $policy->check_no : '') }}"
                        placeholder="Check No."
                    >
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="col-form-label">Company Total *</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text basic-addon-company-total">$</span>
                        </div>
                        <input
                            type="text"
                            class="form-control company-total"
                            name="company_total[]"
                            value="{{
                                old('company_total.' . $policyIndex) ?
                                old('company_total.' . $policyIndex) :
                                ($policy ? $policy->company_total : '')
                            }}"
                            placeholder="Company total"
                            readonly
                            aria-describedby="basic-addon-company-total"
                        >
                    </div>
                    @if ($errors->has('company_total.' . $policyIndex))
                        <div class="float-left alert alert-danger col-md-12">
                            {{ $errors->first('company_total.' . $policyIndex) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr />
</div>