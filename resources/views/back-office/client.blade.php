@extends('back-office.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Client</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="page-title">
                        Client - {{ date("Y") }}-{{ $quote->hash_id }}
                        <a href="/admin/auto/{{ $quote->hash_id }}/edit" class="btn btn-lg btn-outline-success float-right">
                            <i class="fe-edit"></i>
                        </a>
                    </h4>
                    <br/>

                    <form method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12">
                                <h4 class="header-title pb-2">Client Details</h4>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Dated Added</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="dated-added" value="" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Customers Name</label>
                                    <div class="col">
                                        <input type="text" class="form-control phone-no" name="customers-name" value="" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Effective Date</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="effective-date" value="" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Added Date</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="added-date" value="" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Phone Number</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="phone-number" value="" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Policy Number</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="policy-number" value="" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">User Input</label>
                                    <div class="col">
                                        <input type="text" class="form-control" name="user-input" value="" @if (!$isEditing) readonly="true" @endif>
                                    </div>
                                </div>

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
    <script src="/js/back-office/edit-client.js"></script>
@endsection