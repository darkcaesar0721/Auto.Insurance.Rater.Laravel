@extends('back-office.master')

@section('content')
<style>
    @media print {
        .left-side-menu, .navbar-custom {
            display: none;
        }
        .content-page {
            margin-left: 0px;
        }
    }
</style>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Reports</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if (!empty($success))
                <div class="alert alert-secondary" role="alert">
                    {!! $success !!}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h4 class="page-title">
                        Reports
                    </h4>

                    <br/>                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col col-3">
                                        <label class="col-form-label">Effective Date From</label>
                                        <input
                                            class="form-control datepicker text-main-color effective_date_from"
                                            name="effective_date_from"
                                            placeholder="Date From"
                                        >
                                    </div>
                                    <div class="col col-3">
                                        <label class="col-form-label">To</label>
                                        <input
                                            class="form-control datepicker text-main-color effective_date_to"
                                            name="effective_date_to"
                                            placeholder="Date To"
                                        >
                                    </div>
                                    <div class="col col-3">
                                        <label class="col-form-label">Expiration Date From</label>
                                        <input
                                            class="form-control datepicker text-main-color expiration_date_from"
                                            name="expiration_date_from"
                                            placeholder="Date From"
                                        >
                                    </div>
                                    <div class="col col-3">
                                        <label class="col-form-label">To</label>
                                        <input
                                            class="form-control datepicker text-main-color expiration_date_to"
                                            name="expiration_date_to"
                                            placeholder="Date To"
                                        >
                                        <small>Note: Dates are inclusive</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col col-4">
                                        <label class="col-form-label">Company</label>
                                        <select class="form-control company">
                                            <option value="0">All Companies</option>
                                            @foreach ($companies as $c)
                                                <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col col-4">
                                        <label class="col-form-label">Referral Fee &amp; Source</label>
                                        <select class="form-control referral">
                                            <option value="0">All Referral Sources</option>
                                            @foreach ($referralSource as $rs)
                                                <option value="{{ $rs->id }}">{{ $rs->referral_company }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col col-4">
                                        <label class="col-form-label">Policy Type</label>
                                        <select id="policy-type" class="form-control res-policy-type-dropdown" name="policy_type_id">
                                            @foreach ($policyTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                            <div class="auto-club-checkboxes">
                                            <label class="auto-club-box">
                                                <input 
                                                    class="res-auto-club-checkbox"
                                                    type="checkbox" 
                                                    name="auto_club" 
                            
                                                > Auto Club
                                            </label>
                                            <label class="license-box">
                                                <input 
                                                    type="checkbox" 
                                                    class="res-license-checkbox"
                                                    name="auto_club_license_only" 

                                                > License
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="card">
                                    <h5 class="card-header">Policies</h5>
                                    <div class="card-body">
                                        <h5 class="card-title res-policies">-</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card">
                                    <h5 class="card-header">Premium</h5>
                                    <div class="card-body">
                                        <h5 class="card-title res-premium">-</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card">
                                    <h5 class="card-header" style="white-space: nowrap;">Down Payment</h5>
                                    <div class="card-body">
                                        <h5 class="card-title res-down-payment">-</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card">
                                    <h5 class="card-header">Ship Fee</h5>
                                    <div class="card-body">
                                        <h5 class="card-title res-ship-fee">-</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card">
                                    <h5 class="card-header">Broker Fee</h5>
                                    <div class="card-body">
                                        <h5 class="card-title res-broker-fee">-</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="card">
                                    <h5 class="card-header">Referral Fee</h5>
                                    <div class="card-body">
                                        <h5 class="card-title res-referral-fee">-</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="col-form-label">
                                    <input 
                                        type="checkbox"
                                        class="reports_is_endorsment"
                                        name="reports_is_endorsment"
                                        value="1"
                                    > Endorsment Only
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-hover report-table">
                                    <thead>
                                        <th>Effective Date</th>
                                        <th>Expiration Date</th>
                                        <th>Customer Number</th>
                                        <th>Client Name</th>
                                        <th>Policy Number</th>
                                        <th>Premium</th>
                                        <th>Down Payment</th>
                                        <th>Broker Fee</th>
                                        <th>Referral Fee</th>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-success btn-info print-btn" type="button">Print</button>
                                <!-- <button class="btn btn-success btn-block" type="submit">Save</button> -->
                            </div>
                        </div>
                    <!-- </form> -->

                </div>
            </div>
        </div>
    </div>

    <script src="/js/back-office/edit-reports.js?i={{md5(date('His'))}}"></script>
@endsection