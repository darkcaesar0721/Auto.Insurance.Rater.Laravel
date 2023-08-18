@extends('back-office.master')

@section('content')
    <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $client ? 'Edit Client' : 'New Client' }}</h4>
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
                        {{ $client ? 'Edit Client' : 'Add New Client' }}
                    </h4>
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="save-info nav-item nav-link {{ !\Session::has('selectedTab') || (\Session::has('selectedTab') && \Session::get('selectedTab') == 'info') ? 'active' : '' }}" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                                Client Info
                            </a>

                            @if ($client && ($client->auto_club || $client->policy_type_id == \App\PolicyTypes::TYPE_AUTO_CLUB)  &&  $client->policy_type_id != \App\PolicyTypes::TYPE_LICENSE_ONLY)
                                <a class="save-info nav-item nav-link {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'auto-club') ? 'active' : '' }}" id="nav-auto-club-tab" data-toggle="tab" href="#nav-auto-club" role="tab" aria-controls="nav-auto-club" aria-selected="true">
                                    Auto Club
                                </a>
                            @endif
                            @if ($client && ($client->auto_club_license_only || $client->policy_type_id == \App\PolicyTypes::TYPE_LICENSE_ONLY ))
                                <a class="save-info nav-item nav-link {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'license-only') ? 'active' : '' }}" id="nav-auto-club-tab" data-toggle="tab" href="#nav-license-only" role="tab" aria-controls="nav-license-only" aria-selected="true">
                                    License
                                </a>
                            @endif
                            @if ($client && (!in_array($client->policy_type_id, [\App\PolicyTypes::TYPE_AUTO_CLUB, \App\PolicyTypes::TYPE_LICENSE_ONLY]))) 
                                <a class="save-info nav-item nav-link {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'policy') ? 'active' : '' }}" id="nav-policies-tab" data-toggle="tab" href="#nav-policies" role="tab" aria-controls="nav-policies" aria-selected="false">
                                    Policies
                                </a>
                            @endif   
                            @if ($client && $client->client_number)
                                <a class="save-info nav-item nav-link {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'attach') ? 'active' : '' }}" id="nav-attachment-tab" data-toggle="tab" href="#nav-attachments" role="tab" aria-controls="nav-attachments" aria-selected="false">
                                    Attachments
                                </a>
                            @endif
                            @if ($client && ($client->policies->count() || $client->policy_type_id == \App\PolicyTypes::TYPE_AUTO_CLUB))
                                <a class="save-info nav-item nav-link {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'forms') ? 'active' : '' }}" id="nav-forms-tab" data-toggle="tab" href="#nav-forms" role="tab" aria-controls="nav-forms" aria-selected="false">
                                    Forms
                                </a>
                            @endif
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade {{ !\Session::has('selectedTab') || (\Session::has('selectedTab')  && \Session::get('selectedTab') == 'info') ? 'show active' : '' }}" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            @include('back-office.partials.client.main-info')
                        </div>

                        @if ($client && ($client->auto_club || $client->policy_type_id == \App\PolicyTypes::TYPE_AUTO_CLUB) &&  $client->policy_type_id != \App\PolicyTypes::TYPE_LICENSE_ONLY)
                            <div class="tab-pane fade {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'auto-club') ? 'show active' : '' }}" id="nav-auto-club" role="tabpanel" aria-labelledby="nav-auto-club">
                                @include('back-office.partials.client.auto-club', ['client' => $client])
                            </div>
                        @endif
                        @if ($client && ($client->auto_club_license_only || $client->policy_type_id == \App\PolicyTypes::TYPE_LICENSE_ONLY ))
                            <div class="tab-pane fade {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'license-only') ? 'show active' : '' }}" id="nav-license-only" role="tabpanel" aria-labelledby="nav-license-only">
                                @include('back-office.partials.client.license-only', ['client' => $client])
                            </div>
                        @endif
                        @if ($client && ($client->policy_type_id != \App\PolicyTypes::TYPE_AUTO_CLUB || $client->policy_type_id != \App\PolicyTypes::TYPE_LICENSE_ONLY))  
                            <div class="tab-pane fade {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'policy') ? 'show active' : '' }}" id="nav-policies" role="tabpanel" aria-labelledby="nav-policies-tab">
                                @include('back-office.partials.client.policy')
                            </div>
                        @endif
                        <div class="tab-pane fade {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'attach') ? 'show active' : '' }}" id="nav-attachments" role="tabpanel" aria-labelledby="nav-attachment-tab">
                            @include('back-office.partials.client.attachment')
                        </div>

                        <div class="tab-pane fade {{ (\Session::has('selectedTab') && \Session::get('selectedTab') == 'attach') ? 'show active' : '' }}" id="nav-forms" role="tabpanel" aria-labelledby="nav-forms-tab">
                            @include('back-office.partials.client.forms')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="/js/back-office/edit-client.js?t={{ md5(date('H:i:s')) }}"></script>
@endsection
