<form id="forms-tab" method="post" action="/admin/clients/save-forms" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <label class="col-form-label col col-md-12">Forms</label>
                
                <div class="col col-md-6">
                    <table class="table hover">
                        <thead>
                            <th>Form Name</th>
                            <th>Link</th>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i <= 11; $i++)
                                @if ($i == 3)
                                    @php continue @endphp
                                @endif
                                <tr>
                                    <td><label>{{ $formNames[$i-1] }}</label></td>
                                    <td>
                                        <div>
                                            @if ($client)
                                                <a href="/admin/clients/generate-form/{{ $client->id }}/{{ $i }}" target="_blank">
                                                    <i class="mdi mdi-file-pdf"></i>
                                                    Generate
                                                </a>
                                            @endif
                                        </div>
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