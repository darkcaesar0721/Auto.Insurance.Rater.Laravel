@extends('back-office.master')

@section('content')
    <script src="/js/back-office/edit-client.js"></script>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    All clients 
                    <a href="/admin/clients/create" class="btn btn-lg btn-outline-success">
                        Add New
                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <h4 class="header-title">Clients</h4>
                    <table id="" class="table clients-datatable">
                        <thead>
                            <tr>
                                <th>Effective Date</th>
                                <th>Expiration Date</th>
                                <th>Customers Name</th>
                                <th>Customer Number</th>
                                <th>Policy Number</th>
                                <th>Phone Number</th>
                                <th>Policy Type</th>
                                <th>Address</th>
                                <th>Referral Source</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
                @php
                $clientsForNotifications = \App\ClientAutoClub::getClientsForNotification();
                @endphp
                @if(count($clientsForNotifications))
                    <div id="bg_popup">
                        <div id="popup">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-form-label col col-md-12">Forms</label>
                                        
                                        <div class="col col-md-12">
                                            <table class="table hover">
                                                <thead>
                                                    <th>User</th>
                                                    <th>Form Name</th>
                                                    <th>Link</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($clientsForNotifications as $clientForNotifications)
                                                        @for ($i = 1; $i <= 1; $i++)
                                                            <tr>
                                                                <td>
                                                                    {{$clientForNotifications->clients()->value('first_name')}} {{$clientForNotifications->clients()->value('last_name')}}
                                                                </td>
                                                                <td><label>{{ $formNamesAutoClub[$i-1] }}</label></td>
                                                                <td>
                                                                    <div>
                                                                        @if ($clientForNotifications)
                                                                            <a href="/admin/clients/generate-form/{{ $clientForNotifications->client_id }}/{{ 'AUTOCLUB-' . $i }}" target="_blank">
                                                                                <i class="mdi mdi-file-pdf"></i>
                                                                                Generate
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="close" href="#" title="Close" onclick="document.getElementById('bg_popup').style.display='none'; return false;">X</a>
                        </div>
                    </div> 
                @endif
            </div>

        </div>
    </div>


@endsection