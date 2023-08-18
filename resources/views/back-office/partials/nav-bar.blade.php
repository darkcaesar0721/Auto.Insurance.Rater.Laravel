<div class="navbar-custom">
    <ul class="list-unstyled topbar-right-menu float-right mb-0">

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <img src="https://fch.lisboa.ucp.pt/sites/default/files/assets/images/avatar-fch_9.png" alt="user-image" class="rounded-circle">
                <small class="pro-user-name ml-1">
                    {{ Auth::user()->name }}
                </small>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown ">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome !</h6>
                </div>

                <!-- item-->
                {{--<a href="javascript:void(0);" class="dropdown-item notify-item">--}}
                    {{--<i class="fe-user"></i>--}}
                    {{--<span>My Account</span>--}}
                {{--</a>--}}

                <!-- item-->
                @if (Auth::user()->is_admin)
                    <a href="/admin/users/create-user" class="dropdown-item notify-item">
                        <i class="fe-user-plus"></i>
                        <span>Create User</span>
                    </a>

                    <a href="/admin/users" class="dropdown-item notify-item">
                        <i class="fe-users"></i>
                        <span>Show Users</span>
                    </a>

                    <div class="dropdown-divider"></div>
                @endif

                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-settings"></i>
                    <span>Settings</span>
                </a>

                <div class="dropdown-divider"></div>

                <!-- item-->
                <a href="/admin/logout" class="dropdown-item notify-item">
                    <i class="fe-log-out"></i>
                    <span>Logout</span>
                </a>

            </div>
        </li>

    </ul>
    <button class="button-menu-mobile open-left disable-btn">
        <i class="fe-menu"></i>
    </button>
    <div class="app-search py-4">
    </div>
</div>