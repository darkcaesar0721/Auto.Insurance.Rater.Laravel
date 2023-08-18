<form id="policy-tab" method="post" action="/admin/clients/save-policy" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-9">
        </div>
        <div class="col-sm-3">
            <button type="button" class="btn btn-primary btn-block btn-add-policy">Add Policy/Endo</button>
        </div>
    </div>
    <div class="policy-wrapper">
        @if (old('policy_number'))
            @foreach (old('policy_number') as $policyIndex => $policyNumber)
                @include('back-office.partials.client.policy-single', [
                    'policyIndex' => $policyIndex,
                    'policy' => (object) [
                        'policy_number' => old('policy_number.' . $policyIndex),
                        'term' => old('term.' . $policyIndex),
                        'effective_date' => old('effective_date.' . $policyIndex),
                        'company_list_id' => old('company_list_id.' . $policyIndex),
                        'premium' => old('premium.' . $policyIndex),
                        'co_fees' => old('co_fees.' . $policyIndex),
                        'broker_fee' => old('broker_fee.' . $policyIndex),
                        'agency_total' => old('agency_total.' . $policyIndex),
                        'company_total' => old('company_total.' . $policyIndex),
                        'check_no' => old('check_no.' . $policyIndex),
                        'paymentm_method_option' => old('paymentm_method_option.' . $policyIndex),
                        'company_down_payment' => old('company_down_payment.' . $policyIndex),
                        'monthly_payment' => old('monthly_payment.' . $policyIndex),
                        'referral_fee_option' => old('referral_fee_option.' . $policyIndex),
                        'referral_source_id' => old('policy_referral_source.' . $policyIndex),
                        'amount' => old('amount.' . $policyIndex),
                        'total_down_payment' => old('total_down_payment.' . $policyIndex),
                        'is_endorsement' => old('is_endorsement.' . $policyIndex)
                    ],
                    'companies' => $companies,
                    'endorsementAvailable' => false
                ])
            @endforeach
        @elseif ($client && count($client->policies))
            @foreach ($client->policies as $policyIndex => $policy)
                @include('back-office.partials.client.policy-single', [
                    'policyIndex' => $policyIndex,
                    'policy' => $policy,
                    'companies' => $companies,
                    'endorsementAvailable' => $policyIndex > 0
                ])
            @endforeach
        @else
            @include('back-office.partials.client.policy-single', [
                'policyIndex' => 0,
                'policy' => null,
                'companies' => $companies,
                'endorsementAvailable' => false
            ])
        @endif
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