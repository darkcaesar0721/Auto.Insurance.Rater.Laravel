@extends('back-office.master')

@section('content')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Verify Phone Number</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="page-title">
                        Verify Phone Number
                    </h4>
                    <form method="post" action="/admin/clients/verify-phone/{{ $client->id }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col col-3">
                                        <label class="col-form-label">We've sent a confirmation code to +1 {{ $client->cell_phone }}</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="verification_code" 
                                            value="" 
                                            placeholder="Enter Verification Code"
                                        >
                                        @if ($errors->has('verification_code'))
                                            <div class="alert alert-danger">
                                                {{ $errors->first('verification_code') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <button class="btn btn-success btn-block" type="submit">Confirm</button>
                            </div>
                            <div class="col-sm-9">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="/js/back-office/edit-client.js?t={{ md5(date('H:i:s')) }}"></script>
@endsection