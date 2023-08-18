<div class="left-side-menu">
    <div class="slimscroll-menu">

        <a href="/admin" class="logo text-center mb-3">
            <span class="logo-lg">
                <img src="/images/insura-logo@2.png">
            </span>
            <span class="logo-sm">
                <img src="/images/insura-logo@2.png" height="24">
            </span>
        </a>


        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                {{--<li class="active">--}}
                    {{--<a href="/admin" class="{{ Request::is('/admin') ? 'active' : '' }}">--}}
                        {{--<i class="fe-airplay"></i>--}}
                        {{--<span> Dashboard </span>--}}
                    {{--</a>--}}
                {{--</li>--}}

                <li class="menu-title">Quotes</li>

                <li>
                    <a href="/admin/auto" class="{{ Request::is('/admin/auto') ? 'active' : '' }}">
                        <i class="mdi mdi-car-side"></i>
                        <span> Auto Quotes</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/import-clients" class="{{ Request::is('/admin/import-clients') ? 'active' : '' }}">
                        <i class="mdi mdi-account-multiple"></i>
                        <span> Import Clients @if(\App\WpImportUser::count())
                        ({{\App\WpImportUser::count()}})@endif</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/clients" class="{{ Request::is('/admin/clients') ? 'active' : '' }}">
                        <i class="mdi mdi-account-multiple"></i>
                        <span> Clients  @if(count(\App\ClientAutoClub::getClientsForNotification()))
                        ({{count(\App\ClientAutoClub::getClientsForNotification())}})@endif</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/company" class="{{ Request::is('/admin/company') ? 'active' : '' }}">
                        <i class="mdi mdi-city"></i>
                        <span> Company </span>
                    </a>
                </li>
                <li>
                    <a href="/admin/referral" class="{{ Request::is('/admin/referral') ? 'active' : '' }}">
                        <i class="mdi mdi-human-male-female"></i>
                        <span> Referral </span>
                    </a>
                </li>
                <li>
                    <a href="/admin/report" class="{{ Request::is('/admin/report') ? 'active' : '' }}">
                        <i class="mdi mdi-format-list-bulleted"></i>
                        <span> Reports </span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="clearfix"></div>

    </div>
</div>