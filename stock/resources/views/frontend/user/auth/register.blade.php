<style>
  .vr__page .vr__nav__bar {
    background-color: #ffffff !important;border-bottom: #ffffff !important;

.vr__sign__link {
    color: #0b0808 !important;
}
  }
  
</style>
@extends('frontend.user.layouts.auth')
@section('title', lang('Sign Up', 'user'))
@section('content')<style>
    body {
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed; /* Optional: Fixed background */
        z-index: -1;
    }

    /* You can also add styling to the specific container if needed */
    .vr__sign__form {
        z-index: 1;
        

}

    }
</style>
    <div class="vr__sign__form vr__register">
        <div class="vr__sign__header">
            <p class="h3 mb-1 sign-up-title">Sign up</p>
        </div>
        <div class="sign-body">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="row row-cols-1 row-cols-sm-2 g-3 mb-3">
                    <div class="col">
                        <div class="">
                            <label class="sign-up-fields-label">{{ lang('First Name', 'forms') }}</label>
                            <div class = "d-flex g-3 sign-up-fields-input-container align-items-center">
                                <img src="{{ asset('images/bgs/person-icon.png') }}"/>
                                <input id="firstname" type="firstname" name="firstname" class="form-control sign-up-fields-input"
                                    placeholder="Enter your firstname" maxlength="50"
                                    value="{{ old('firstname') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="">
                            <label class="sign-up-fields-label">{{ lang('Last Name', 'forms') }}</label>
                            <div class = "d-flex g-3 sign-up-fields-input-container align-items-center">
                                <img src="{{ asset('images/bgs/person-icon.png') }}"/>
                                <input id="lastname" type="lastname" name="lastname" class="form-control sign-up-fields-input"
                                placeholder="Enter your lastname" maxlength="50" value="{{ old('lastname') }}"
                                required>
                                    
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" mb-3">
                    <label class="sign-up-fields-label">{{ lang('Username', 'forms') }}</label>
                    <div class = "d-flex g-3 sign-up-fields-input-container align-items-center">
                        <img src="{{ asset('images/bgs/person-icon.png') }}"/>
                        <input id="username" type="username" name="username" class="form-control sign-up-fields-input"
                            placeholder="Enter your username" minlength="6" maxlength="50"
                            value="{{ old('username') }}" required>
                    </div>
                </div>
                <div>
                    <label class="sign-up-fields-label">{{ lang('Phone Number', 'forms') }}</label>
                    <div class="form-number mb-3 align-items-center sign-up-select-container">
                        <select id="mobile_code" name="mobile_code" class="form-select flex-shrink-0 w-auto sign-up-fields-input sign-up-fields-select">
                            @foreach (countries() as $country)
                                <option data-code="{{ $country->code }}" data-id="{{ $country->id }}"
                                    value="{{ $country->id }}" @if ($country->id == old('mobile_code')) selected @endif>
                                    {{ $country->code }}
                                    ({{ $country->phone }})
                                </option>
                            @endforeach
                        </select>
                        <div class = "sign-up-right-border"></div>
                        <div class=" w-100">
                            <input id="mobile" type="tel" name="mobile" class="form-control sign-up-fields-input"
                                value="{{ old('mobile') }}" required>
                        </div>
                    </div>
                </div>
                <label class="sign-up-fields-label">E-mail</label>
                <div class=" mb-3">
                <div class = "d-flex g-3 sign-up-fields-input-container align-items-center">
                    <img src="{{ asset('images/bgs/mail.png') }}"/>
                    <input id="email" type="email" name="email" class="form-control sign-up-fields-input"
                        placeholder="Enter your e-mail" value="{{ old('email') }}" required>
                </div>
                </div>
                <label class="sign-up-fields-label">{{ lang('Password', 'forms') }}</label>
                <div class=" mb-3">
                <div class = "d-flex g-3 sign-up-fields-input-container align-items-center">
                    <img src="{{ asset('images/bgs/lock.png') }}"/>
                    <input id="password" type="password" name="password" class="form-control sign-up-fields-input"
                        placeholder="Enter your password" minlength="8" required>
                    <img src="{{ asset('images/bgs/eye.png') }}"/>
                </div>
                </div>
                <label class="sign-up-fields-label">{{ lang('Confirm password', 'forms') }}</label>
                <div class=" mb-3">
                <div class = "d-flex g-3 sign-up-fields-input-container align-items-center">
                    <img src="{{ asset('images/bgs/lock.png') }}"/>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control sign-up-fields-input"
                        placeholder="Re-enter your password" minlength="8" required>
                    <img src="{{ asset('images/bgs/eye.png') }}"/>
                </div>  
                </div>
                @if ($settings['terms_of_service_link'])
                    <div class="form-check mb-3">
                        <input id="terms" name="terms" class="form-check-input" type="checkbox"
                            @if (old('terms')) checked @endif required>
                        <label class="form-check-label">
                            {{ lang('I agree to the', 'user') }} <a href="{{ $settings['terms_of_service_link'] }}"
                                class="vr__link__color">{{ lang('terms of service', 'user') }}</a>
                        </label>
                    </div>
                @endif
                {!! display_captcha() !!}
                <div class="d-flex justify-content-between align-items-center">
                    <label class="sign-up-custom-checkbox remember-me">
                        <input type="checkbox">
                        <span class="sign-up-checkmark"></span>
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class = "remember-me">Forgot password?</a>
                </div>
                <div class="d-flex">
                    <button class="btn btn-secondary btn-lg w-100 sign-up-button">Signup</button>
                </div>
                <p class = "already">Alreay have an account?&nbsp;&nbsp;<a href = "/en/login" class="sign-in">Sign in</a></p>
                {!! facebook_login() !!}
            </form>
        </div>
    </div>

    <div class = "d-flex gap-4 justify-content-center">
        <p class = "already">Contact</p>
        <p class = "already">Terms of Use</p>
        <p class = "already">Privacy Policy</p>
    </div>
@endsection
