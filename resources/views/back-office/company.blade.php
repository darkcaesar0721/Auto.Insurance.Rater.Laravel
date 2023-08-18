@extends('back-office.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    All companies 
                    @if (Auth::user()->is_admin)
                        <a href="/admin/company/create" class="btn btn-lg btn-outline-success">
                            Add New
                        </a>
                    @endif
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <h4 class="header-title">All companies</h4>
                    <br/>

                    <table id="" class="table dt-responsive nowrap basic-datatable">
                        <thead>
                            <tr>
                                <th>Date Added</th>
                                <th>Company Name</th>
                                <th>Claims Phone</th>
                                <th>Email</th>
                                <th>Website</th>
                                @if (Auth::user()->is_admin)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($companies as $c)
                                <tr class="pointer company-table-row" data-company-id="{{ $c->id }}">
                                    <td>{{ $c->created_at->format('m/d/Y') }}</td>
                                    <td>{{ $c->company_name }}</td>
                                    <td>{{ $c->claims_phone }}</td>
                                    <td>{{ $c->email }}</td>
                                    <td>{{ $c->website }}</td>
                                    @if (Auth::user()->is_admin)
                                        <td><a href="/admin/company/delete/{{ $c->id }}"><i class="delete-icon mdi mdi-delete-forever"></i></a></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>


@endsection