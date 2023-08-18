@extends('back-office.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Auto-Rater Quotes</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Auto Quotes</h4>
                    @if (Auth::user()->is_admin)
                        <div class="col-12 align-items-end p-0">
                            @if (Request::is('admin/auto/deleted'))
                                <a href="/admin/auto" class="pb-2">(See Quotes)</a>
                            @else
                                <a href="/admin/auto/deleted" class="pb-2">(See Deleted Quotes)</a>
                            @endif
                        </div>
                    @endif
                    <br/>

                    <table id="basic-datatable" class="table dt-responsive nowrap">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Card Authorized</th>
                            <th>Email Verified</th>
                            <th>Uploaded Files</th>
                            <th>Date</th>
                            <th>Actions</th>
                            <th>Address</th>
                            <th>License Numbers</th>
                        </tr>
                        </thead>


                        <tbody>
                        @foreach ($quotes as $quote)
                            <tr class="pointer quote-table-row" data-hash-id="{{ $quote->hash_id }}">
                                <td class="expand-icon">{{ $quote->drivers[0]->first_name ?? '' }} {{ $quote->drivers[0]->last_name ?? '-' }}</td>
                                <td>{{ $quote->email ?? '-'}}</td>
                                <td>{{ $quote->phone ?? '-' }}</td>
                                <td>@if ($quote->card_authorized) <i class="fe-check-circle text-success"></i> @else - @endif</td>
                                <td>@if ($quote->email_verified) <i class="fe-check-circle text-success"></i> @else - @endif</td>
                                <td>{{ $quote->files_count }}</td>
                                <td>{{ $quote->created_at->format('M d Y') }}</td>
                                @if (Auth::user()->is_admin)
                                    @if (Request::is('admin/auto'))
                                        <td>
                                            <a href="/admin/auto/{{ $quote->hash_id }}/import">
                                                <i class="save-icon mdi mdi-import"></i>
                                            </a>
                                            
                                            @if (Auth::user()->is_admin) 
                                                <a href="/admin/auto/{{ $quote->hash_id }}/delete">
                                                    <i class="fe-trash-2"></i>
                                                </a>    
                                            @endif
                                        </td>
                                    @elseif (Request::is('admin/auto/deleted'))
                                        <td>
                                            <a href="/admin/auto/{{ $quote->hash_id }}/recover">
                                                <i class="fe-refresh-cw"></i>
                                            </a>
                                            <a href="/admin/auto/{{ $quote->hash_id }}/delete-entirely" class="ml-2">
                                                <i class="fe-trash-2"></i>
                                            </a>
                                        </td>
                                    @endif
                                @endif
                                <td>{{ $quote->address ?? '-' }}</td>
                                <td>
                                    @foreach ($quote->drivers as $i => $driver)
                                        {{ $driver->license_no }} @if ($i+1 < $quote->drivers->count()), @endif
                                    @endforeach
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