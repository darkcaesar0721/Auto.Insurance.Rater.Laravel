@extends('back-office.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    Referral Source 
                    @if (Auth::user()->is_admin)
                        <a href="/admin/referral/create" class="btn btn-lg btn-outline-success">
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

                    <h4 class="header-title">Referral Source</h4>
                    <br/>

                    <table id="" class="table nowrap basic-datatable">
                        <thead>
                            <tr>
                                <th>Date Added</th>
                                <th>Company</th>
                                <th>Contact Name</th>
                                <th>Cell</th>
                                <th>Website</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referralSource as $rs)
                                <tr data-referral-id="{{ $rs->id }}">
                                    <td>{{ $rs->created_at->format('m/d/Y') }}</td>
                                    <td>{{ $rs->referral_company }}</td>
                                    <td>{{ $rs->referral_contact_name }}</td>
                                    <td>{{ $rs->referral_cell }}</td>
                                    <td>
                                        @if ($rs->referral_website)
                                            <a href="{{ $rs->referral_website }}">Link</a>
                                        @endif
                                    </td>
                                    <!-- {{ strlen($rs->referral_website) > 45 ? (substr($rs->referral_website, 0, 45) . ' ...') : $rs->referral_website }} -->
                                    <td>
                                        <a href="/admin/referral/edit/{{ $rs->id }}"><i class="edit-icon mdi mdi-pencil"></i></a>
                                            @if (Auth::user()->is_admin && $rs->referral_company != "DISCOUNT AUTO CLUB - LATIN AUTO CLUB")
                                                <a href="/admin/referral/delete/{{ $rs->id }}"><i class="delete-icon mdi mdi-delete-forever"></i></a>
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