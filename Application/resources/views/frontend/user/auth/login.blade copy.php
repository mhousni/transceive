<style>
  .vr__page .vr__nav__bar {
    background-color: transparent !important;border-bottom: none !important;

.vr__sign__link {
    color: #0b0808 !important;
}
  }
  
</style>
@extends('frontend.user.layouts.auth')
@section('title', lang('Sign In', 'user'))
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
</style>
    <div class="d-flex gap-5 mx-1 px-1 px-lg-5 mx-lg-5 align-items-center h-90-vh">
        <img class="d-none d-lg-block" src="{{ asset('images/bgs/card-bg.png') }}"/>
        <div class="flex-grow-1">
            <div class="vr__sign__form vr__register">
                <div class="vr__sign__header">
                        <p class="h3 mb-1 sign-up-title">Login</p>
                    </div>
                    <div class="sign-body">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <label class="sign-up-fields-label">E-mail</label>
                            <div class="mb-3">
                            <div class = "d-flex g-3 sign-up-fields-input-container align-items-center">
                                <img src="{{ asset('images/bgs/mail.png') }}"/>
                                <input type="email" name="email" id="email" class="form-control sign-up-fields-input"
                                    placeholder="{{ lang('Email address', 'forms') }}" value="{{ old('email') }}" required>
                            </div>
                            </div>
                            <label class="sign-up-fields-label">{{ lang('Password', 'forms') }}</label>
                            <div class="mb-3">
                            <div class = "d-flex g-3 sign-up-fields-input-container align-items-center">
                                <img src="{{ asset('images/bgs/lock.png') }}"/>
                                <input type="password" name="password" id="password" class="form-control sign-up-fields-input"
                                    placeholder="{{ lang('Password', 'forms') }}" required>
                                <img onclick="ma()" src="{{ asset('images/bgs/eye.png') }}"/>
                            </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="sign-up-custom-checkbox remember-me">
                                    <input name="remember" type="checkbox">
                                    <span class="sign-up-checkmark"></span>
                                    Remember me
                                </label>
                                <a href="{{ route('password.request') }}" class = "remember-me">Forgot password?</a>
                            </div>
                            {!! display_captcha() !!}
                            <div class="d-flex">
                                <button type="submit" class="btn btn-secondary  btn-lg w-100 sign-up-button bg-primary-light">Signup</button>
                            </div>
                            <p class = "already">Dont have an account yet?&nbsp;&nbsp;<a href = "/en/register" class="sign-in">Sign up</a></p>
                            {!! facebook_login() !!}
                        </form>
                    </div>
            </div>
        </div>
    </div>
    <div class = "d-flex gap-4 justify-content-center">
        <p class = "already">Contact</p>
        <p class = "already">Terms of Use</p>
        <p class = "already">Privacy Policy</p>
    </div>
    <script>
        function ma() {
          var x = document.getElementById("password");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
        }
        </script>
@endsection
