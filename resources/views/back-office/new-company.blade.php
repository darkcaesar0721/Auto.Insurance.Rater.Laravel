@extends('back-office.master')

@section('content')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $company ? 'Edit Company' : 'New Company' }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            
            @if (\Session::has('success'))
                <div class="alert alert-secondary" role="alert">
                    {!! \Session::get('success') !!}
                </div>
            @endif
           
            <div class="card">
                <div class="card-body">
                    <h4 class="page-title">
                        {{ $company ? 'Edit Company' : 'Add New Company' }}
                    </h4>
                    
                    <form method="post" action="/admin/company/save-company-info" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row client-name-fields">
                                    <div class="col">
                                        <div class="row">
                                           <div class="client-name-fields-container col-sm-8">
                                                <label class="col-form-label">Company Name</label>
                                                <input 
                                                    type="text"
                                                    class="form-control capitalize"
                                                    name="company_name"
                                                    placeholder="Company Name"
                                                    value="{{ old('company_name') ? old('company_name') : ($company ? $company->company_name : '') }}"
                                                >
                                                @if ($errors->has('company_name'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('company_name') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-4">
                                                <label class="col-form-label">Broker ID</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    name="broker_id" 
                                                    placeholder="Broker ID"
                                                    value="{{ old('broker_id') ? old('broker_id') : ($company ? $company->broker_id : '') }}"
                                                >
                                                @if ($errors->has('broker_id'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('broker_id') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                           <div class="client-name-fields-container col-sm-4">
                                                <label class="col-form-label">Toll Free</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control toll-free" 
                                                    name="toll_free" 
                                                    placeholder="Toll Free"
                                                    value="{{ old('toll_free') ? old('toll_free') : ($company ? $company->toll_free : '') }}"
                                                >
                                                @if ($errors->has('toll_free'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('toll_free') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-4">
                                                <label class="col-form-label">Claims Phone</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control claims-phone" 
                                                    name="claims_phone" 
                                                    placeholder="Claims Phone"
                                                    value="{{ old('claims_phone') ? old('claims_phone') : ($company ? $company->claims_phone : '') }}"
                                                >
                                                @if ($errors->has('claims_phone'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('claims_phone') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-4">
                                                <label class="col-form-label">Fax</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control fax" 
                                                    name="fax" 
                                                    placeholder="Fax"
                                                    value="{{ old('fax') ? old('fax') : ($company ? $company->fax : '') }}"
                                                >
                                                @if ($errors->has('fax'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('fax') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                           <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">Email</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    name="email" 
                                                    placeholder="Email"
                                                    value="{{ old('email') ? old('email') : ($company ? $company->email : '') }}"
                                                >
                                                @if ($errors->has('email'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('email') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">Website</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control"
                                                    name="website"
                                                    placeholder="Website"
                                                    value="{{ old('website') ? old('website') : ($company ? $company->website : '') }}"
                                                >
                                                @if ($errors->has('website'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('website') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">Note</label>
                                                <textarea placeholder="Note" class="form-control" name="note" rows="8">{{ old('note') ? old('note') : ($company && $company->note ? $company->note : '') }}</textarea>
                                            </div>
                                            <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">Payment Address</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control"
                                                    name="payment_address"
                                                    placeholder="Payment Address"
                                                    value="{{ old('payment_address') ? old('payment_address') : ($company ? $company->payment_address : '') }}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h2>Files:</h2>
                                @if (count($files))
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">File name</th>
                                                <th scope="col">Link</th>
                                                <th scope="col">Date Added</th>
                                                @if (Auth::user()->is_admin)
                                                    <th scope="col">Delete</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($files as $index => $file)
                                                <tr>
                                                    <th scope="row">{{ $index+1 }}</th>
                                                    <td>{{ $file['name'] }}</td>
                                                    <td><a href="{{$file['url']}}" target="_blank">Link</a> </td>
                                                    <td>{{$file['created_at']}}</td>
                                                    @if (Auth::user()->is_admin)
                                                        <td>
                                                            <a href="{{route('clients.file.delete', ['folder' => $file['folder'], 'bucket' => 's3-company', 'file' =>  $file['name']] )}}">Delete</a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h4>No Files Found</h4>
                                @endif

                                <hr />

                                <h2>Upload File</h2>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <input 
                                            type="file" 
                                            class="capitalize upload_files" 
                                            name="attachment_file_1" 
                                        >
                                        @if ($errors->has('attachment_file_1'))
                                            <div class="float-left alert alert-danger col-md-12">
                                                {{ $errors->first('attachment_file_1') }}
                                            </div>
                                        @endif
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
                        <input type="hidden" name="id" value="{{ $company ? $company->id : 0 }}">
                    </form>
                                
                    
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="/js/back-office/edit-client.js?t={{ md5(date('H:i:s')) }}"></script>
    <script src="/js/back-office/edit-company.js?t={{ md5(date('H:i:s')) }}"></script>
@endsection