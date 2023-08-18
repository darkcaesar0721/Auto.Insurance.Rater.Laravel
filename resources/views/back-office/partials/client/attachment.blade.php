<form id="attachment-tab" method="post" action="/admin/clients/save-attachment" enctype="multipart/form-data">
    {{ csrf_field() }}
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
                                        <a href="{{route('clients.file.delete', ['folder' => $file['folder'], 'bucket' => 's3', 'file' =>  $file['name']] )}}">Delete</a>
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
    <input type="hidden" name="client_id" value="{{ $client ? $client->id : 0 }}">
</form>