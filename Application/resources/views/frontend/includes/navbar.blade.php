<nav class="nav-bar">
    <div class="container-fluid d-flex align-items-center">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset($settings['website_light_logo']) }}" alt="{{ $settings['website_name'] }}" />
            <img src="{{ asset($settings['website_dark_logo']) }}" alt="{{ $settings['website_name'] }}" />
        </a>
        <div class="nav-bar-actions ms-auto">
            <div class="nav-bar-menu">
                <div class="overlay"></div>
                <div class="nav-bar-links">
                    <div class="d-flex justify-content-end w-100 mb-3 d-lg-none">
                        <button class="btn-close"></button>
                    </div>
                    @foreach ($navbarMenuLinks as $navbarMenuLink)
                        <a class="nav-bar-link" {!! !$navbarMenuLink->type
                            ? 'href="' . $navbarMenuLink->link . '"'
                            : 'data-link="' . $navbarMenuLink->link . '"' !!}>
                            {{ $navbarMenuLink->name }}
                        </a>
                    @endforeach
                    @guest
                        {{-- @if ($settings['website_registration_status'])
                            <a class="nav-bar-link btn" href="{{ route('register') }}">
                                {{ lang('Sign Up', 'user') }}
                            </a>
                        @endif --}}
                        <a class="login-button" href="{{ route('login') }}">
                            {{ lang('Sign In', 'user') }} / {{ lang('Sign Up', 'user') }}
                        </a>
                    @endguest
                </div>
            </div>
            @auth
                <div class="drop-down user-menu ms-3" data-dropdown="" data-dropdown-position="top">
                    <div class="drop-down-btn">
                        <img src="{{ asset(userAuthInfo()->avatar) }}" alt="{{ userAuthInfo()->name }}"
                            class="user-img" />
                        <span class="user-name">{{ userAuthInfo()->name }}</span>
                        <i class="fa fa-angle-down ms-2"></i>
                    </div>
                    <div class="drop-down-menu p-2">
                        <a class="drop-down-item d-flex gap-2 align-items-center" href="{{ route('user.dashboard') }}">
                            {{-- <i class="fa-solid fa-table-columns"></i> --}}
                            <img src="{{ asset('images/icons/dash/dashboard-dark.svg') }}" class="" width="30" height="30" alt="">
                            {{ lang('Dashboard', 'user') }}
                        </a>
                        <a class="drop-down-item d-flex gap-2 align-items-center" href="{{ route('user.settings') }}">
                            {{-- <i class="fa fa-cog"></i> --}}
                            <img src="{{ asset('images/icons/dash/setting.svg') }}" class="" width="30" height="30" alt="">
                            {{ lang('Settings', 'user') }}
                        </a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="drop-down-item d-flex gap-2 align-items-center text-danger">
                            {{-- <i class="fa fa-power-off"></i> --}}
                            <img src="{{ asset('images/icons/dash/logout.svg') }}" class="" width="30" height="30" alt="">
                            {{ lang('Logout', 'user') }}
                        </a>
                    </div>
                    <form id="logout-form" class="d-inline" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </div>
            @endauth
            <div class="nav-bar-menu-icon d-lg-none">
                <i class="fa fa-bars fa-lg"></i>
            </div>
        </div>
    </div>
</nav>
