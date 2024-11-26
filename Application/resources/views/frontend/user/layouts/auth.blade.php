<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('frontend.user.includes.head')
    @include('frontend.user.includes.styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="{{ asset('/assets/css/application.css') }}">
    <style>
            .swiper {
      width: 100%;
      height: 100%;
    }

    .swiper-slide {
      /* text-align: center; */
      font-size: 18px;
      display: flex;
      /* justify-content: center; */
      /* align-items: center; */
    }
    .login-signup {
        display: flex;
    align-items: center;
    }
    .swiper-button-next , .swiper-button-prev {
        position: relative !important;
        width: auto !important;
        height: auto !important;
        padding-right: 20px !important;
        padding-left: 20px !important;
        border-radius: 20px !important;
    }

    .swiper-button-next:after, .swiper-rtl .swiper-button-prev:after {
        content: "" !important;
    }
    .swiper-button-prev:after, .swiper-rtl .swiper-button-next:after {
        content: "" !important;
    }

    /* .swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    } */
    </style>
</head>

<body>
    <main class="signup-login-page d-flex flex-column justify-content-between align-items-between" style="height: 100vh;">
        <img class="bg position-absolute start-0 bottom-0" src="{{ asset('/images/Group 3.svg') }}" alt="">
        <img class="bg position-absolute end-0" src="{{ asset('/images/Group 2.svg') }}" alt="">
        <div class="vr__page @yield('bg')" style="background: url({{ asset('/images/grey-background-shape.svg') }})">
            <nav class="vr__nav__bar">
                <div class="vr__nav__container h-100">
                    <div class="row align-items-center h-100">
                        <div class="col-auto">
                            <a class="logo d-flex align-items-center ps-3 mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none p-2" href="{{ route('home') }}">
                                <img src="{{ asset($settings['website_light_logo']) }}"
                                    alt="{{ $settings['website_name'] }}" />
                            </a>
                        </div>
                        <div class="col-auto ms-auto">
                            <div class="vr__signs">
                                @auth
                                    <form class="d-inline" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="btn vr__sign__link"><i
                                                class="fas fa-sign-out-alt me-2"></i>{{ lang('Logout', 'user') }}</button>
                                    </form>
                                
                                @endauth
                                @guest
                                <ul class="d-flex gap-1 list-unstyled ">
                                    <li class="text-black"><div class="swiper-button-next p-2 ps-3 pe-3 pl-3 pr-3 rounded-5  bg-light text-white " style="background-color: #111350 !important;">Sign up</div></li>
                                    <li class="text-black"><div class="swiper-button-prev p-2 rounded-5 bg-light text-white" style="background-color: #111350 !important;">Login</div></li>
                                </ul>
                                @endguest
                            </div>
                           
                        </div>
                    </div>
                </div>
            </nav>
            <div class="vr__form__aria px-2 position-relative">
                {{-- <img style="position:absolute; z-index:1; top:-80px; right:0;" src="{{ asset('images/bgs/top-right.png') }}"/>
                <img style="position:absolute; z-index:1; bottom:0; left:0;" src="{{ asset('images/bgs/bottom-left.png') }}"/> --}}
                <div class="position-relative py-5">
                    @yield('content')
                </div>
            </div>
            <div class="vr__footer">
                <div class="vr__nav__container">
                    <div class="row justify-content-end align-items-center g-3">
                        
                        <div class="col-auto">
                            <div class="vr__lang">
                                <div class="vr__lang__icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <select class="vr__change__language form-select">
                                    @foreach ($languages as $language)
                                        <option data-link="{{ langURL($language->code) }}"
                                            value="{{ $language->code }}"
                                            @if (app()->getLocale() == $language->code) selected @endif>
                                            {{ $language->name }}</option>
                                    @endforeach
                                </select>
                                <div class="select-icon">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="vr__copyright">
                                <p class="mb-0 small">&copy; <span data-year></span> {{ $settings['website_name'] }}
                                    -
                                    {{ lang('All rights reserved') }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </main>
    @include('frontend.configurations.config')
    @include('frontend.configurations.widgets')
    @include('frontend.user.includes.scripts')
    {!! google_captcha() !!}
    @include('frontend.user.includes.toastr')

   
</body>

</html>
