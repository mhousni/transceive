<footer class="footer pb-0">
    <div class="container-lg footer-container" style="">
        <div class="footer-logo-news">
            <div class="footer-logo mb-2">
                <img
                    src="{{ asset('images/bgs/white-logo.png') }}"
                    alt="{{ $settings['website_name'] }}"
                    width="200px"
                />
            </div>
            <div class="d-flex flex-column gap-2">
                <p class="footer-title">{{ lang('Solutions', 'home page') }}</p>
                <a href="#" class="footer-subtext"><i class="fa-brands fa-x-twitter p-2 rounded-pill bg-white text-black me-2"></i> Twitter</a>
                <a href="#" class="footer-subtext"><i class="fa-brands fa-facebook  p-2 rounded-pill bg-white text-black me-2"></i> Instagram</a>
                <a href="#" class="footer-subtext"><i class="fa-brands fa-instagram  p-2 rounded-pill bg-white text-black me-2"></i> Facebook</a>
            </div>
        </div>
        {{-- <div class="footer-contact">
            <p class="footer-title">Contact</p>
            <p class="footer-description">support@transceive.ca</p>
        </div> --}}

        <div class="footer-linksies">
            <div>
                <p class="footer-title">{{ lang('Company', 'home page') }}</p>
                {{-- <a href="#" class="footer-subtext">About</a><br/>
                <a href="#" class="footer-subtext">Team</a><br/> --}}
                <a href="#" class="footer-subtext">{{ lang('Privacy Policy', 'home page') }}</a><br/>
                <a href="#" class="footer-subtext">{{ lang('Terms and Conditions', 'home page') }}</a><br/>
            </div>
            <div class="footer-subscribe-container">
                <p class="footer-subscribe-heading">{{ lang('Subscribe to our newsletter', 'home page') }}</p>
                <div class="footer-subscribe-button">
                    <input
                        type="text"
                        class="footer-subscribe-input"
                        placeholder="Enter your email"
                    />
                    <button class="footer-subscribe-submit">{{ lang('Subscribe', 'home page') }}</button>
                </div>
                <p class="footer-subscribe-subtext">
                    {{ lang('By subscribing to our newsletter, you agree with our Terms &
                    Conditions', 'home page') }}
                    {{-- By subscribing to our newsletter,<br/>you agree with our Terms &
                    Conditions --}}
                </p>
            </div>
            
        </div>

        
    </div>
    <div class="vr__footer pt-0 pb-2 pl-2 pr-2">
        <div class="vr__nav__container">
            <div class="row justify-content-center align-items-center g-3">
                
                <div class="col-auto">
                    <div class="vr__lang d-flex justify-content-center align-items-center bg-black gap-1">
                        <div class="vr__lang__icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <select class="vr__change__language form-select bg-black text-white-50 border-0">
                            @foreach ($languages as $language)
                                <option data-link="{{ langURL($language->code) }}"
                                    value="{{ $language->code }}"
                                    @if (app()->getLocale() == $language->code) selected @endif>
                                    {{ $language->name }}</option>
                            @endforeach
                        </select>
                        {{-- <div class="select-icon">
                            <i class="fas fa-caret-down"></i>
                        </div> --}}
                    </div>
                </div>
                <div class="col-auto">
                    <div class="vr__copyright footer-copyright">
                        <p class="mb-0 small">&copy; <span data-year></span> {{ $settings['website_name'] }}
                            -
                            {{ lang('All rights reserved') }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    {{-- <div class="footer-copyright">
        <p class="mb-0">&copy; <span data-year></span> {{ $settings['website_name'] }} -
            {{ lang('All rights reserved') }}.</p>
    </div> --}}
</footer>
