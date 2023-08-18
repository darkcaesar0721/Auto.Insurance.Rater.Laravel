@extends('back-office.master')

@section('content')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $referralSource ? 'Edit Referral Source' : 'New Referral Source' }}</h4>
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
                       {{ $referralSource ? 'Edit Referral Source' : 'Add New Referral Source' }}
                    </h4>
                    
                    <form method="post" action="/admin/referral/save-referral-info" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row client-name-fields">
                                    <div class="col">
                                        <div class="row">
                                           <div class="client-name-fields-container col-sm-12">
                                                <label class="col-form-label">Company</label>
                                                <input 
                                                    type="text"
                                                    class="form-control capitalize"
                                                    name="referral_company"
                                                    placeholder="Company"
                                                    value="{{ old('referral_company') ? old('referral_company') : ($referralSource ? $referralSource->referral_company : '') }}"
                                                >
                                                @if ($errors->has('referral_company'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_company') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                           <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">Contact First Name</label>
                                                <input 
                                                    type="text"
                                                    class="form-control capitalize"
                                                    name="referral_first_name"
                                                    placeholder="First Name"
                                                    value="{{ old('referral_first_name') ? old('referral_first_name') : ($referralSource ? $referralSource->referral_first_name : '') }}"
                                                >
                                                @if ($errors->has('referral_first_name'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_first_name') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">Contact Last Name</label>
                                                <input 
                                                    type="text"
                                                    class="form-control capitalize"
                                                    name="referral_last_name"
                                                    placeholder="Last Name"
                                                    value="{{ old('referral_last_name') ? old('referral_last_name') : ($referralSource ? $referralSource->referral_last_name : '') }}"
                                                >
                                                @if ($errors->has('referral_last_name'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_last_name') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">Address Line 1</label>
                                                <input 
                                                    type="text"
                                                    class="form-control capitalize"
                                                    name="referral_address_line_1"
                                                    placeholder="Suite"
                                                    value="{{ old('referral_address_line_1') ? old('referral_address_line_1') : ($referralSource ? $referralSource->referral_address_line_1 : '') }}"
                                                >
                                                @if ($errors->has('referral_address_line_1'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_address_line_1') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">Address Line 2</label>
                                                <input 
                                                    type="text"
                                                    class="form-control capitalize"
                                                    name="referral_address_line_2"
                                                    placeholder="Suite"
                                                    value="{{ old('referral_address_line_2') ? old('referral_address_line_2') : ($referralSource ? $referralSource->referral_address_line_2 : '') }}"
                                                >
                                                @if ($errors->has('referral_address_line_2'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_address_line_2') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="client-name-fields-container col-sm-6">
                                                <label class="col-form-label">City</label>
                                                <input 
                                                    type="text"
                                                    class="form-control capitalize"
                                                    name="referral_city"
                                                    placeholder="City"
                                                    value="{{ old('referral_city') ? old('referral_city') : ($referralSource ? $referralSource->referral_city : '') }}"
                                                >
                                                @if ($errors->has('referral_city'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_city') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-2">
                                                <label class="col-form-label">State</label>
                                                <select class="form-control" name="referral_state_id">
                                                    @foreach ($states as $state)
                                                        <option 
                                                            value="{{ $state->id }}" 
                                                            @if(old('referral_state_id') == $state->id || ($referralSource && $referralSource->referral_state_id == $state->id)) selected 
                                                            @elseif(!old('referral_state_id') && !$referralSource && $state->name == 'CA') selected
                                                            @endif
                                                        >{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="client-name-fields-container col-sm-4">
                                                <label class="col-form-label">ZIP</label>
                                                <input 
                                                    type="text"
                                                    class="form-control referral-zip"
                                                    name="referral_zip"
                                                    placeholder="ZIP"
                                                    value="{{ old('referral_zip') ? old('referral_zip') : ($referralSource ? $referralSource->referral_zip : '') }}"
                                                >
                                                @if ($errors->has('referral_zip'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_zip') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="client-name-fields-container col-sm-4">
                                                <label class="col-form-label">Work</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control referral-work"
                                                    name="referral_work"
                                                    placeholder="Work"
                                                    value="{{ old('referral_work') ? old('referral_work') : ($referralSource ? $referralSource->referral_work : '') }}"
                                                >
                                                @if ($errors->has('referral_work'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_work') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-4">
                                                <label class="col-form-label">Cell</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control referral-cell"
                                                    name="referral_cell"
                                                    placeholder="Cell"
                                                    value="{{ old('referral_cell') ? old('referral_cell') : ($referralSource ? $referralSource->referral_cell : '') }}"
                                                >
                                                @if ($errors->has('referral_cell'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_cell') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-4">
                                                <label class="col-form-label">Fax</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control referral-fax"
                                                    name="referral_fax"
                                                    placeholder="Fax"
                                                    value="{{ old('referral_fax') ? old('referral_fax') : ($referralSource ? $referralSource->referral_fax : '') }}"
                                                >
                                                @if ($errors->has('referral_fax'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_fax') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                           <div class="client-name-fields-container col-sm-3">
                                                <label class="col-form-label">Email</label>
                                                <input 
                                                    type="text"
                                                    class="form-control"
                                                    name="referral_email"
                                                    placeholder="Email"
                                                    value="{{ old('referral_email') ? old('referral_email') : ($referralSource ? $referralSource->referral_email : '') }}"
                                                >
                                                @if ($errors->has('referral_email'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_email') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-3">
                                                <label class="col-form-label">Website</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control"
                                                    name="referral_website"
                                                    placeholder="Website"
                                                    value="{{ old('referral_website') ? old('referral_website') : ($referralSource ? $referralSource->referral_website : '') }}"
                                                >
                                                @if ($errors->has('referral_website'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_website') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-3">
                                                <label class="col-form-label">License</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control"
                                                    name="referral_license"
                                                    placeholder="License"
                                                    value="{{ old('referral_license') ? old('referral_license') : ($referralSource ? $referralSource->referral_license : '') }}"
                                                >
                                                @if ($errors->has('referral_license'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_license') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="client-name-fields-container col-sm-3">
                                                <label class="col-form-label">SSN / Tax ID:</label>
                                                <input 
                                                    type="text" 
                                                    class="form-control"
                                                    name="referral_tax_id"
                                                    placeholder="###-##-#### or ##-#######"
                                                    value="{{ old('referral_tax_id') ? old('referral_tax_id') : ($referralSource ? $referralSource->referral_tax_id : '') }}"
                                                >
                                                @if ($errors->has('referral_tax_id'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('referral_tax_id') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="client-name-fields-container col-sm-12">
                                                <label class="col-form-label">Note</label>
                                                <textarea placeholder="Note" class="form-control" name="note" rows="8">{{ old('note') ? old('note') : ($referralSource && $referralSource->note ? $referralSource->note : '') }}</textarea>
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
                                                            <a href="{{route('clients.file.delete', ['folder' => $file['folder'], 'bucket' => 's3-referral', 'file' =>  $file['name']] )}}">Delete</a>
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
                        <input type="hidden" name="id" value="{{ $referralSource ? $referralSource->id : 0 }}">
                    </form>
                                
                    
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="/js/back-office/edit-client.js?t={{ md5(date('H:i:s')) }}"></script>
    <script src="/js/back-office/edit-referral.js?t={{ md5(date('H:i:s')) }}"></script>
@endsection