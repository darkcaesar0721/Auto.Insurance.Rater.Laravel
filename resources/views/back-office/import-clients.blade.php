@extends('back-office.master')

@section('content')
    <script src="/js/back-office/edit-client.js"></script>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    All clients
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <h4 class="header-title">Clients</h4>
                    <table id="" class="table import-datatable">
                        <thead>
                            <tr>
                                <th>Membership Number</th>
                                <th>Given names</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Plan</th>
                                <th>Order type</th>
                                <th>Imported</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach ($clients as $c)
                            <tr class="import-client-table-row" data-client-id="{{ $c->id }}">
                                <td>
                                    @if (isset($c['membership_number']))
                                        {{ $c['membership_number'] }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    {{ $c['given_names'].' '.$c['surname'] }}
                                </td>
                                <td>
                                    {{ $c['address_line_1'].' '.$c['address_line_2'] }}
                                </td>
                                <td>
                                    @if (isset($c['phone']))
                                            {{ $c['phone'] }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($c['email']))
                                            {{ $c['email'] }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($c['plan']))
                                            {{ $c['plan'] }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($c['order_type']))
                                            {{ $c['order_type'] }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if (isset($c['created_at']))
                                            {{ $c['created_at'] }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    <a href="/admin/client/create-import/{{ $c->id }}"><i class="save-icon mdi mdi-content-save"></i></a>
                                    @if (Auth::user()->is_admin)
                                        <a href="/admin/client/delete-import/{{ $c->id }}"><i class="delete-icon mdi mdi-delete-forever"></i></a>
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


@endsection