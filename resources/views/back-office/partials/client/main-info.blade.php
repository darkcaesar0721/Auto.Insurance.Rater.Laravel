<form id="main-info-tab" class="main-info-client" method="post" action="/admin/clients/save-main-info">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-12">
            <div class="form-group row">
                <div class="col col-3">
                    <label class="col-form-label">Created Date</label>
                    <input
                        id="datepicker"
                        class="form-control text-main-color" 
                        name="created_date" 
                        value="{{ $client ? $client->created_at->format('m/d/Y') : (new \DateTime())->format('m/d/Y')}}"
                        placeholder="Created Date"
                        disabled
                    >
                    @if ($errors->has('created_date'))
                        <div class="alert alert-danger">
                            {{ $errors->first('created_date') }}
                        </div>
                    @endif
                </div>

                <div class="col col-3">
                    <label class="col-form-label">Source</label>
                    <select
                        class="form-control"
                        name="source"
                    >
                        <option 
                            value="input"
                            @if(old('source') == 'input' || ($client && $client->source == 'input')) selected @endif
                        >Manual Input</option>
                        <option 
                            value="import"
                            @if(old('source') == 'import' || ($client && $client->source == 'import')) selected @endif
                        >Imported</option>
                    </select>
                    @if ($errors->has('source'))
                        <div class="alert alert-danger">
                            {{ $errors->first('source') }}
                        </div>
                    @endif
                </div>
                <div class="col col-3">
                    <label class="col-form-label">Agent</label>
                    <select id="agent_id" class="form-control" name="agent_id">
                        @foreach ($agents as $user)
                            <option 
                                value="{{ $user->id }}" 
                                @if(old('agent_id') == $user->id || ($client && $client->agent_id == $user->id)) selected @endif
                            >{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col col-3">
                    <label class="col-form-label">Language Spoken</label>
                    <select
                        class="form-control"
                        name="language_spoken"
                    >
                        <option 
                            value="english"
                            @if(old('language_spoken') == 'english' || ($client && $client->language_spoken == 'english')) selected @endif
                        >English</option>
                        <option 
                            value="spanish"
                            @if(old('language_spoken') == 'spanish' || ($client && $client->language_spoken == 'spanish')) selected @endif
                        >Spanish</option>
                        <option 
                            value="other"
                            @if(old('language_spoken') == 'other' || ($client && $client->language_spoken == 'other')) selected @endif
                        >Other</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12">
            <label class="col-form-label">Client Type</label>
            <select id="client-type" class="form-control" name="client_type_id">
                @foreach ($clientTypes as $type)
                    <option 
                        value="{{ $type->id }}" 
                        @if(old('client_type_id') == $type->id || ($client && $client->client_type_id == $type->id)) selected @endif
                    >{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 client-number">
            <label class="col-form-label">Client #</label>
            <div class="input-group">
                <div class="input-group-append">
                    <button disabled class="btn btn-outline-secondary" type="button">
                        {{ $client && $client->client_number ? substr($client->client_number, 0, -6) : date("Y") . '-' }}
                    </button>
                </div>

                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Client Number" 
                    aria-label="Client Number" 
                    aria-describedby="basic-addon2"
                    name="client_number" 
                    value="{{ old('client_number') ? old('client_number') : ($client ? substr($client->client_number, 5) : \App\ClientNumber::generateClientNo()) }}" 
                >
            </div>
            @if ($errors->has('client_number'))
                <div class="alert alert-danger">
                    {{ $errors->first('client_number') }}
                </div>
            @endif
        </div>

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12">
            <label class="col-form-label">Policy Type</label>
            <select id="policy-type" class="form-control policy-type-dropdown" name="policy_type_id">
                @foreach ($policyTypes as $type)
                    <option 
                        value="{{ $type->id }}" 
                        @if(old('policy_type_id') == $type->id) selected 
                        @elseif(!old('policy_type_id') && ($client && $client->policy_type_id == $type->id))
                        selected
                        @endif
                    >{{ $type->name }}</option>
                @endforeach
            </select>
            <div class="auto-club-checkboxes">
            <label class="auto-club-box">
                <input 
                    id="auto-club-checkbox"
                    type="checkbox" 
                    name="auto_club" 
                    value="1" 
                    @if (old('auto_club') || ($client && $client->auto_club)) checked @endif
                > Auto Club
            </label>
            <label class="license-box">
                <input 
                    type="checkbox" 
                    id="license-checkbox"
                    name="auto_club_license_only" 
                    value="1" 
                    @if (old('auto_club_license_only') || ($client && $client->auto_club_license_only)) checked @endif
                > License
            </label>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 business-name-block form-group">
            <label class="col-form-label">Business Name</label>
            <input 
                type="text" 
                class="form-control capitalize" 
                name="business_name" 
                value="{{ old('business_name') ? old('business_name') : ($client ? $client->business_name : '') }}" 
                placeholder="Business Name"
            >
            @if ($errors->has('business_name'))
                <div class="client-name-fields-container float-left alert alert-danger col-md-12">
                    {{ $errors->first('business_name') }}
                </div>
            @endif
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <h4 class="header-title pb-2 client-name-text">Client's name</h4>
            <h4 class="header-title pb-2 contact-person-text">Contact Person</h4>
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="client-name-fields-container col-md-3">
                            <label class="col-form-label">First Name</label>
                            <input 
                                type="text" 
                                class="form-control capitalize" 
                                name="first_name" 
                                value="{{ old('first_name') ? old('first_name') : ($client ? $client->first_name : '') }}" 
                                placeholder="First Name"
                            >
                            @if ($errors->has('first_name'))
                                <div class="client-name-fields-container float-left alert alert-danger col-md-12">
                                    {{ $errors->first('first_name') }}
                                </div>
                            @endif
                        </div>
                        <div class="client-name-fields-container col-md-3">
                            <label class="col-form-label">Middle Name</label>
                            <input 
                                type="text" 
                                class="form-control capitalize" 
                                name="middle_name" 
                                value="{{ old('middle_name') ? old('middle_name') : ($client ? $client->middle_name : '') }}" 
                                placeholder="Middle Name"
                            >
                        </div>
                        <div class="client-name-fields-container col-md-3">
                            <label class="col-form-label">Last Name</label>
                            <input 
                                type="text" 
                                class="form-control capitalize" 
                                name="last_name" 
                                value="{{ old('last_name') ? old('last_name') : ($client ? $client->last_name : '') }}" 
                                placeholder="Last Name"
                            >
                            @if ($errors->has('last_name'))
                                <div class="client-name-fields-container float-left alert alert-danger col-md-12">
                                    {{ $errors->first('last_name') }}
                                </div>
                            @endif
                        </div>
                        <div class="client-name-fields-container col-md-3">
                            <label class="col-form-label">Suffix</label>
                            <input 
                                type="text" 
                                class="form-control capitalize" 
                                name="suffix" 
                                value="{{ old('suffix') ? old('suffix') : ($client ? $client->suffix : '') }}" 
                                placeholder="Suffix"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row client-characteristics">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <h4 class="header-title pb-2 client-name-text">Client's characteristics</h4>
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="row">
                        <!-- <div class="client-name-fields-container client-characteristics-container col-md-3">
                            <label class="col-form-label">Country</label>
                            <select id="client-country" class="form-control" name="client_country_id">
                                @foreach ($clientCountries as $country)
                                    <option 
                                        value="{{ $country->id }}" 
                                        @if(old('client_country_id') == $country->id || ($client && $client->client_country_id == $country->id)) selected
                                        @elseif(!old('client_country_id') && !$client && $country->country == "Mexico") selected
                                        @endif
                                    >{{ strtoupper($country->country) }}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <div class="client-name-fields-container client-characteristics-container col-md-4">
                            <label class="col-form-label">Nationality</label>
                            <select id="client-nationality" class="form-control" name="nationality_id">
                                @foreach ($clientCountries as $country)
                                    <option 
                                        value="{{ $country->id }}" 
                                        @if(old('nationality_id') == $country->id || ($client && $client->getNationality() == $country->country)) selected
                                        @elseif(!old('nationality_id') && !$client && $country->country == "Mexico") selected
                                        @endif
                                    >{{ strtoupper($country->country) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="client-name-fields-container client-characteristics-container col-md-4">
                            <label class="col-form-label">Height</label>
                            <select id="client-height" class="form-control" name="client_height_id">
                                @foreach ($clientHeights as $height)
                                    <option 
                                        value="{{ $height->id }}" 
                                        @if(old('client_height_id') == $height->id || ($client && $client->client_height_id == $height->id)) selected 
                                        @elseif(!old('client_height_id') && !$client && $height->height == "5.4\" - 162.56 CM") selected
                                        @endif
                                    >{{ $height->height }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="client-name-fields-container client-characteristics-container col-md-4">
                            <label class="col-form-label">Eyes</label>
                            <select id="client-eyes" class="form-control" name="client_eyes_id">
                                @foreach ($clientEyes as $eyes)
                                    <option 
                                        value="{{ $eyes->id }}" 
                                        @if(old('client_eyes_id') == $eyes->id || ($client && $client->client_eyes_id == $eyes->id)) selected 
                                        @elseif(!old('client_eyes_id') && !$client && $eyes->eyes == "BROWN") selected
                                        @endif
                                    >{{ $eyes->eyes }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="client-name-fields-container client-characteristics-container col-md-4">
                            <label class="col-form-label">Class</label>
                            <select id="client-class" class="form-control" name="client_class_id">
                                @foreach ($clientClasses as $class)
                                    <option 
                                        value="{{ $class->id }}" 
                                        @if(old('client_class_id') == $class->id || ($client && $client->client_class_id == $class->id)) selected 
                                        @elseif(!old('client_class_id') && !$client && $class->class == "B - PASSENGER CAR") selected
                                        @endif
                                    >{{ $class->class }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="client-name-fields-container  col-md-4">
                            <label class="col-form-label">Gender</label>
                            <select id="client-sex" class="form-control" name="client_sex_id">
                                @foreach ($clientSex as $sex)
                                    <option 
                                        value="{{ $sex->id }}" 
                                        @if(old('client_sex_id') == $sex->id || ($client && $client->client_sex_id == $sex->id)) selected 
                                        @elseif(!old('client_sex_id') && !$client && $sex->sex == "MALE") selected
                                        @endif
                                    >{{ $sex->sex }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="client-name-fields-container col-md-4">
                            <label class="col-form-label">Date of Birth (MM/DD/YYYY)</label>
                            <!-- effective_date -->
                            <input
                                class="form-control datepicker text-main-color"
                                name="client_date_of_birth" 
                                value="{{ old('client_date_of_birth') ? old('client_date_of_birth') : ($client && $client->client_date_of_birth ? $client->client_date_of_birth : '') }}"
                                placeholder="Date of Birth"
                            >
                            @if ($errors->has('client_date_of_birth'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('client_date_of_birth') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
            <h4 class="header-title pb-2">Current Address</h4>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">Address Line 1</label>
                <div class="col">
                    <input 
                        type="text" 
                        class="form-control capitalize" 
                        name="current_address_line_1" 
                        value="{{ old('current_address_line_1') ? old('current_address_line_1') : ($client ? $client->current_address_line_1 : '') }}" 
                        placeholder="Address Line 1"
                    >
                    @if ($errors->has('current_address_line_1'))
                        <div class="alert alert-danger">
                            {{ $errors->first('current_address_line_1') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">Address Line 2</label>
                <div class="col">
                    <input 
                        type="text" 
                        class="form-control capitalize" 
                        name="current_address_line_2" 
                        value="{{ old('current_address_line_2') ? old('current_address_line_2') : ($client ? $client->current_address_line_2 : '') }}" 
                        placeholder="Address Line 2"
                    >
                    @if ($errors->has('current_address_line_2'))
                        <div class="alert alert-danger">
                            {{ $errors->first('current_address_line_2') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">City</label>
                <div class="col col-8">
                    <div class="row">
                        <div class="col-12">
                            <input 
                                type="text" 
                                class="form-control city-field capitalize" 
                                name="current_address_address_city" 
                                value="{{ old('current_address_address_city') ? old('current_address_address_city') : ($client ? $client->current_address_address_city : '') }}" 
                                placeholder="City"
                            >
                            @if ($errors->has('current_address_address_city'))
                                <div class="alert alert-danger city-field">
                                    {{ $errors->first('current_address_address_city') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">State/ZIP</label>
                <div class="col col-8">
                    <div class="row">
                        <div class="col-7">
                            <select class="form-control" name="current_address_address_state_id" id="current_address_address_state_id">
                                @foreach ($states as $state)
                                    <option 
                                        value="{{ $state->id }}" 
                                        @if(old('current_address_address_state_id') == $state->id || ($client && $client->current_address_address_state_id == $state->id)) selected 
                                        @elseif(!old('current_address_address_state_id') && !$client && $state->name == 'CA') selected
                                        @endif
                                    >{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <div id="international-box">
                                <label>
                                    <input 
                                        type="checkbox" 
                                        id="international-checkbox"
                                        name="international" 
                                        value="1"
                                        @if ($client && !$client->current_address_address_state_id) checked @endif
                                    > International
                                </label>
                            </div>
                        </div>
                        <div class="col-5">
                            <input 
                                type="text" 
                                class="form-control zip-code-field" 
                                name="current_address_zip_code" 
                                value="{{ old('current_address_zip_code') ? old('current_address_zip_code') : ($client ? $client->current_address_zip_code : '') }}" 
                                placeholder="Zip-Code"
                            >
                            @if ($errors->has('current_address_zip_code'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('current_address_zip_code') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">Mailing Address</label>
                <div class="col col-8">
                    <label class="col-form-label">
                        <input 
                            type="checkbox" 
                            name="mailing_address" 
                            class="mailing-address-checkbox"
                            value="1" 
                            @if (old('mailing_address') || ($client && $client->mailing_address)) checked @endif
                        > <small> (Check if different than Current address) </small>
                    </label>
                </div>
            </div>
            <div class="form-group row mailing-address {{ (old('mailing_address') || ($client && $client->mailing_address)) ? '' : 'd-none' }}">
                <label class="col-form-label col-sm-4">Address Line 1</label>
                <div class="col">
                    <input 
                        type="text" 
                        class="form-control capitalize" 
                        name="mailing_address_line_1" 
                        value="{{ old('mailing_address_line_1') ? old('mailing_address_line_1') : ($client ? $client->mailing_address_line_1 : '') }}" 
                        placeholder="Address Line 1"
                    >
                    @if ($errors->has('mailing_address_line_1'))
                        <div class="alert alert-danger">
                            {{ $errors->first('mailing_address_line_1') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group row mailing-address {{ (old('mailing_address') || ($client && $client->mailing_address)) ? '' : 'd-none' }}">
                <label class="col-form-label col-sm-4">Address Line 2</label>
                <div class="col">
                    <input 
                        type="text" 
                        class="form-control capitalize" 
                        name="mailing_address_line_2" 
                        value="{{ old('mailing_address_line_2') ? old('mailing_address_line_2') : ($client ? $client->mailing_address_line_2 : '') }}" 
                        placeholder="Address Line 2"
                    >
                    @if ($errors->has('mailing_address_line_2'))
                        <div class="alert alert-danger">
                            {{ $errors->first('mailing_address_line_2') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group row mailing-address {{ (old('mailing_address') || ($client && $client->mailing_address)) ? '' : 'd-none' }}">
                <label class="col-form-label col-sm-4">City</label>
                <div class="col col-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <input 
                                type="text" 
                                class="form-control city-field capitalize" 
                                name="mailing_address_city" 
                                value="{{ old('mailing_address_city') ? old('mailing_address_city') : ($client ? $client->mailing_address_city : '') }}" 
                                placeholder="City"
                            >
                            @if ($errors->has('mailing_address_city'))
                                <div class="alert alert-danger city-field">
                                    {{ $errors->first('mailing_address_city') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row mailing-address {{ (old('mailing_address') || ($client && $client->mailing_address)) ? '' : 'd-none' }}">
                <label class="col-form-label col-sm-4">State/ZIP</label>
                <div class="col col-8">
                    <div class="row">
                        <div class="col-sm-7">
                            <select class="form-control" name="mailing_address_state_id">
                                @foreach ($states as $state)
                                    <option 
                                        value="{{ $state->id }}" 
                                        @if(old('mailing_address_state_id') == $state->id || ($client && $client->mailing_address_state_id == $state->id)) selected 
                                        @elseif(!old('mailing_address_state_id') && !$client && $state->name == 'CA') selected
                                        @endif
                                    >{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input 
                                type="text" 
                                class="form-control zip-code-field" 
                                name="mailing_address_zip_code" 
                                value="{{ old('mailing_address_zip_code') ? old('mailing_address_zip_code') : ($client ? $client->mailing_address_zip_code : '') }}" 
                                placeholder="ZIP-Code"
                            >
                            @if ($errors->has('mailing_address_zip_code'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('mailing_address_zip_code') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
            <h4 class="header-title pb-2">Contact Information</h4>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">
                    Cell Phone
                    <a href="https://voice.google.com/u/2/messages?itemId=draft" target="_blank"> <i class="mdi mdi-message-processing"></i> </a>
                </label>
                <div class="col">
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control phone-no cell-phone" 
                            placeholder="Cell Phone" 
                            aria-label="Cell Phone" 
                            aria-describedby="basic-addon2"
                            name="cell_phone" 
                            value="{{ old('cell_phone') ? old('cell_phone') : ($client ? $client->cell_phone : '') }}" 
                        >
                        <div class="input-group-append">
                            <input id="add-cell-phone" class="btn btn-outline-secondary add-cell-phone" type="button" value="Add">
                        </div>
                    </div>
                    <div id="second-cell-phone">
                        <input
                            type="text" 
                            class="form-control second-cell-phone phone-no" 
                            name="cell_phone_2" 
                            value="{{ old('cell_phone_2') ? old('cell_phone_2') : ($client ? $client->cell_phone_2 : '') }}" 
                            placeholder="Second Cell Phone"
                        >
                    </div>
                    @if ($errors->has('cell_phone'))
                        <div class="alert alert-danger">
                            {{ $errors->first('cell_phone') }}
                        </div>
                    @endif
                    @if ($errors->has('cell_phone_2'))
                        <div class="alert alert-danger">
                            {{ $errors->first('cell_phone_2') }}
                        </div>
                    @endif
                    {!! 
                        $client && $client->verification ? 
                        '<i class="mdi mdi-account-check"></i> <small>Verified</small>' : 
                        '<i class="mdi mdi-account-remove"></i> <small>Unverified</small>'
                    !!}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">
                    Email Address
                    <a href="https://mail.google.com/mail/u/2/?tab=wm#inbox" target="_blank"> <i class="mdi mdi-email"></i> </a>
                </label>
                <div class="col">
                    <input 
                        type="text" 
                        class="form-control email-address" 
                        name="email_address" 
                        value="{{ old('email_address') ? old('email_address') : ($client ? $client->email_address : '') }}" 
                        placeholder="Email Address"
                    >
                    @if ($errors->has('email_address'))
                        <div class="alert alert-danger">
                            {{ $errors->first('email_address') }}
                        </div>
                    @endif
                    <input 
                        type="checkbox"
                        name="no_email"
                        class="no-email"
                        value="1"
                        @if (old('no_email') || ($client && $client->no_email)) checked @endif
                    > <small> (No email) </small>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">Home Phone</label>
                <div class="col">
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control phone-no copy-phone" 
                            placeholder="Home Phone" 
                            aria-label="Home Phone" 
                            aria-describedby="basic-addon2"
                            name="home_phone" 
                            value="{{ old('home_phone') ? old('home_phone') : ($client ? $client->home_phone : '') }}" 
                        >
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary add-home-phone" type="button">Same As Cell</button>
                        </div>
                    </div>

                    @if ($errors->has('home_phone'))
                        <div class="alert alert-danger">
                            {{ $errors->first('home_phone') }}
                        </div>
                    @endif

                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">Work Phone</label>
                <div class="col">
                    <input 
                        type="text" 
                        class="form-control phone-no" 
                        name="work_phone" 
                        value="{{ old('work_phone') ? old('work_phone') : ($client ? $client->work_phone : '') }}" 
                        placeholder="Work Phone"
                    >
                    @if ($errors->has('work_phone'))
                        <div class="alert alert-danger">
                            {{ $errors->first('work_phone') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="form-group row fax-number">
                <label class="col-form-label col-sm-4">Fax Number</label>
                <div class="col">
                    <input 
                        type="text" 
                        class="form-control client-fax-number" 
                        name="fax_number" 
                        value="{{ old('fax_number') ? old('fax_number') : ($client ? $client->fax_number : '') }}" 
                        placeholder="Fax Number"
                    >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4">Preferred Contact Method</label>
                <div class="col">
                    <select class="form-control contact-method" name="preferred_contact_method_id">
                        <option value="">--- Contact Method ---</option>
                        @foreach ($contactMethods as $method)
                            <option 
                                value="{{ $method->id }}" 
                                @if(old('preferred_contact_method_id') == $method->id || ($client && $client->preferred_contact_method_id == $method->id)) selected @endif
                            >{{ $method->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('preferred_contact_method_id'))
                        <div class="alert alert-danger">
                            {{ $errors->first('preferred_contact_method_id') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-12">
            <h4 class="header-title pb-2">Additional Insured</h4>
            <div class="form-group row client-name-fields">
                <div class="col">
                   <div class="row">
                       <div class="client-name-fields-container col-sm-3">
                            <label class="col-form-label">First Name</label>
                            <input 
                                type="text" 
                                class="form-control capitalize" 
                                name="additional_insured_first_name" 
                                value="{{ old('additional_insured_first_name') ? old('additional_insured_first_name') : ($client ? $client->additional_insured_first_name : '') }}" 
                                placeholder="First Name"
                            >
                        </div>
                        <div class="client-name-fields-container col-sm-3">
                            <label class="col-form-label">Middle Name</label>
                            <input 
                                type="text" 
                                class="form-control capitalize" 
                                name="additional_insured_middle_name" 
                                value="{{ old('additional_insured_middle_name') ? old('additional_insured_middle_name') : ($client ? $client->additional_insured_middle_name : '') }}" 
                                placeholder="Middle Name"
                            >
                        </div>
                        <div class="client-name-fields-container col-sm-3">
                            <label class="col-form-label">Last Name</label>
                            <input 
                                type="text" 
                                class="form-control capitalize" 
                                name="additional_insured_last_name" 
                                value="{{ old('additional_insured_last_name') ? old('additional_insured_last_name') : ($client ? $client->additional_insured_last_name : '') }}" 
                                placeholder="Last Name"
                            >
                        </div>
                        <div class="client-name-fields-container col-sm-3">
                            <label class="col-form-label">Suffix</label>
                            <input 
                                type="text" 
                                class="form-control capitalize" 
                                name="additional_insured_suffix" 
                                value="{{ old('additional_insured_suffix') ? old('additional_insured_suffix') : ($client ? $client->additional_insured_suffix : '') }}" 
                                placeholder="Suffix"
                            >
                        </div>
                        <div class="client-name-fields-container checkbox-co-applicant col-sm-12">
                            <label class="col-form-label">
                                <input 
                                    type="checkbox"
                                    name="additional_insured_co_applicant"
                                    value="1"
                                    @if(old('additional_insured_co_applicant') || ($client && $client->additional_insured_co_applicant)) checked @endif
                                > Co-Applicant
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="client-name-fields-container col-sm-12">
                            <label class="col-form-label">Notes</label>
                            <textarea rows="8" placeholder="Notes" class="form-control" name="notes">{{ (old('notes') ? old('notes') : ($client && $client->notes ? $client->notes : '')) }}</textarea>
                        </div>
                    </div>
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
    <input type="hidden" name="client_id" id="client_id" value="{{ $client ? $client->id : 0 }}">
</form>