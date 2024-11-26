@extends('frontend.user.layouts.dash')
@section('title', lang('Dashboard', 'user'))
@section('search', true)
@section('content')
    <div class="row g-2 mb-0">
        <div class="col-lg-4">
            <div class="vr__counter__box bg-dashboard h-100">
                <div class="bx mb-3">
                    <h3 class="vr__counter__box__title">{{ lang('Your storage space', 'user') }}</h3>
                    <p class="vr__counter__box__number vr__counter__box__number_first  text-white">
                        {{ subscription()->storage->used_space }}
                        <span class="text-white">{{ lang('of', 'user') }}</span>
                        {{ subscription()->plan->storage_space }}
                    </p>
                    @if (subscription()->storage->remaining_space)
                        <span> ({{ subscription()->storage->remaining_space }}) </span><span class="text-white-50">{{ lang('Remaining', 'user') }}</span>
                    @endif
                    <span class="vr__counter__box__icon pb-2 pe-3">
                        {{-- <i class="fas fa-database"></i>dd --}}
                        <img src="{{ asset('images/icons/dash/icon_07.svg') }}" width="60" height="60" alt="">
                        
                    </span>
                    <img src="{{ asset('images/icons/dash/lines.svg') }}" class="line-pattern-img" width="60" height="60" alt="">
                </div>
                @if (!is_null(subscription()->plan->storage_space_number))
                    @php
                        if (subscription()->storage->used_percentage > 80) {
                            $bg = 'bg-danger';
                        } elseif (subscription()->storage->used_percentage > 50 && subscription()->storage->used_percentage < 80) {
                            $bg = 'bg-warning';
                        } else {
                            $bg = 'bg-success';
                        }
                    @endphp
                    <div class="progress">
                        <div class="progress-bar {{ $bg }}" role="progressbar"
                            style="width: {{ subscription()->storage->used_percentage }}%"
                            aria-valuenow="{{ subscription()->storage->used_percentage }}" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-4">
            <div class="vr__counter__box bg-white h-100">
                <div class="bx mb-2">
                    <h3 class="vr__counter__box__title text-black-50">{{ lang('Total transfers', 'user') }}</h3>
                    <p class="vr__counter__box__number ">{{ shortNumber($totalTransfers) }}</p>
                    <span class="vr__counter__box__icon pb-2 pe-3">
                        {{-- <i class="fas fa-paper-plane"></i> --}}
                        <img src="{{ asset('images/icons/dash/icon_08.svg') }}" width="60" height="60" alt="">
                        
                    </span>
                    <img src="{{ asset('images/icons/dash/lines-white.svg') }}" class="line-pattern-img" width="60" height="60" alt="">
                </div>
                {{-- <a href="{{ url('/') }}" class="btn btn-primary"><i
                        class="far fa-paper-plane me-2"></i>{{ lang('Start Transfer', 'user') }}</a> --}}
            </div>
        </div>
        <div class="col-lg-4">
            <div class="vr__counter__box bg-white h-100">
                <div class="bx mb-2">
                    <h3 class="vr__counter__box__title text-black-50">{{ lang('Day Left', 'user') }} </h3>
                    <p class="vr__counter__box__number">{{subscription()->remining_days}} {{ lang('Days', 'user') }}</p>
                    <span class="vr__counter__box__icon pb-2 pe-3">
                        {{-- <i class="fas fa-paper-plane"></i> --}}
                        <img src="{{ asset('images/icons/dash/icon_02.svg') }}" width="60" height="60" alt="">
                    </span>
                    <img src="{{ asset('images/icons/dash/lines-white.svg') }}" class="line-pattern-img" width="60" height="60" alt="">
                </div>
                {{-- <a href="{{ url('/') }}" class="btn btn-primary"><i
                        class="far fa-paper-plane me-2"></i>{{ lang('Start Transfer', 'user') }}</a> --}}
            </div>
        </div>
    </div>
    <div class="header-content">
        <div class="container-lg">
            <div id="dropzone-wrapper" class="dropzone-wrapper">
                <div class="dropzone-index d-flex justify-content-between align-items-center flex-wrap flex-lg-nowrap">
                    @unless (subscription()->is_subscribed)
                    <div>
                        {{-- <div class="header-content-icon" data-aos="fade" data-aos-duration="1000" data-dz-click>
                        
                        </div>
                        <p class="h2 mb-4 text-white" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="250">
                            {{ lang('Transfer your files, easy and secure', 'home page') }}
                        </p>
                        <div class="col-lg-6 mx-auto mb-5" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="500">
                            <p class="text-white lead mb-0">
                                {{ lang('Transfer your files Up to 20GB* per transfer and have them travel around the world for free, easily and securely.', 'home page') }}
                            </p>
                        </div> --}}
                        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="750">
                            <h2 class = "home-title text-white">{{ lang('Transfer your files, easy and secure', 'home page') }}</h2>
                            <p class = "home-description text-white">{{ lang('Transfer your files Up to 20GB* per transfer and have them travel around the world for free, easily and securely.', 'home page') }}</p>
                            {{-- @if (subscription()->is_subscribed) --}}
                                {{-- <button class="btn btn-primary btn-md get-started" data-dz-click>
                                    <i class="fa-solid fa-paper-plane me-2"></i>
                                    {{ lang('Start Transfer', 'home page') }}
                                    
                                </button> --}}
                                {{-- <a href="{{ route('login') }}" class="align-self-start bg-white rounded-pill ps-4 pe-4 pt-2 pb-2 text-decoration-none">
                                    {{ lang('Start Transfer', 'home page') }}
                                </a> --}}
                            {{-- @else --}}
                                {{-- <a href="{{ route('login') }}" class="btn btn-secondary btn-md get-started">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ lang('Get Started', 'home page') }}
                                </a> --}}
                                <a href="{{ route('login') }}" class="home-btn-cta align-self-start bg-white rounded-pill ps-4 pe-4 pt-2 pb-2 text-decoration-none">
                                    {{ lang('Get Started', 'home page') }}
                                </a>
                            {{-- @endif --}}
                        </div>
                    </div>
                    <div>
                    <img
                        src="{{ asset('images/bgs/mobile-bg.svg') }}"
                        class="header-content-image"
                    />
                    </div>
                    @endunless 
                </div>
                @if (subscription()->is_subscribed)
                
                    {{-- <div class="dropzone-drag" data-dz-click>
                        <div class="dropzone-drag-inner">
                            <div class="dropzone-drag-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <p class="dropzone-drag-title">
                                {{ lang('Drag and Drop Your Files to Start Transfer', 'upload zone') }}</p>
                            <p class="dropzone-drag-text">{{ lang('Or click here', 'upload zone') }}</p>
                        </div>
                    </div> --}}
                    <div class="dropzone-uploadbox  show-forms">
                        <h2 class="ps-3">{{ lang('Upload File', 'upload zone') }}</h2>
                        <div class="dropzone-form-actions  d-flex g-5 p-3" style="gap: 20px">
                            
                            @if (subscription()->plan->transfer_link)
                                <div class="text-primary dropzone-form-action drop-active selected">
                                    <i class="fa fa-link"></i>
                                    {{ lang('Link', 'upload zone') }}
                                </div>
                            @endif
                            <div class="text-primary dropzone-form-action">
                                <i class="fa fa-envelope"></i>
                                {{ lang('Email', 'upload zone') }}
                            </div>
                        </div>
                        <div class="dropzone-uploadbox-upper">
                            <div class="dropzone-uploadbox-header d-flex flex-column">
                                <div class="dropzone-more d-flex justify-content-center align-items-center w-100 " data-dz-click>
                                    
                                    <div class="ms-0 d-flex flex-column align-items-center gap-2">
                                        <img
                                            src="{{ asset('images/icons/addfile.svg') }}"
                                            class="header-content-image" width="30"
                                        />
                                        <h2 class=" dropzone-more-title text-main display-4 text-center f-bold fw-bold">{{ lang('Add Files', 'upload zone') }}</h2>
                                        <p class="text-primary fw-light  text-center"><span class="text-secondary">{{ lang('Drag and Drop Files, Or', 'upload zone') }}</span> <span class="text-decoration-underline ">{{ lang('Browse', 'upload zone') }}</span></p>
                                        <p class="dropzone-more-text text-center">
                                            {{ lang('Total Files', 'upload zone') }} <span data-dz-length></span>, <span
                                                data-dz-fullsize></span>
                                        </p>
                                    </div>
                                    <i class="fa fa-plus fa-2x text-white bg-secondary p-3 faplusicon"></i>
                                </div>
                                <div class="increase d-flex w-100 gap-5 justify-content-between p-2">
                                    <span class="text-blackss fw-light" style="color: #666666">{{ lang('Up to 10 GB free', 'upload zone') }}</span>
                                    <a href="#" class="" style="color: #414273; font-weight:bold"> 
                                        <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.85328 0.190881V5.59608H12.4375L5.80805 14.9453V8.64568H0.56975L7.85328 0.190881Z" fill="#414273"/>
                                        </svg>
                                            
                                        {{ lang('Increase the limit', 'upload zone') }}
                                            </a>
                                </div>
                               
                                {{-- <div class="dropzone-reset" data-dz-reset>
                                    <i class="fa-solid fa-rotate-right"></i>
                                    <p class="dropzone-reset-text">{{ lang('Reset', 'upload zone') }}</p>
                                </div> --}}
                               
                            </div>
                            
                            <div class="dropzone-uploadbox-files" data-simplebar>
                                <div id="dropzone" class="dropzone"></div>
                            </div>
                            <div class="dropzone-uploadbox-lower">
                                {{-- <div class="dropzone-form-actions  ">
                                    @if (subscription()->plan->transfer_link)
                                        <div class="text-primary dropzone-form-action drop-active selected">
                                            <i class="fa fa-link"></i>
                                            {{ lang('Link', 'upload zone') }}
                                        </div>
                                    @endif
                                    <div class="text-primary dropzone-form-action">
                                        <i class="fa fa-envelope"></i>
                                        {{ lang('Email', 'upload zone') }}
                                    </div>
                                </div> --}}
                                <div class="dropzone-forms">
                                    @if (subscription()->plan->transfer_link)
                                        <div class="dropzone-form show animation selected">
                                            <div class="dropzone-form-header">
                                                {{-- <p class="dropzone-form-title text-white">
                                                    <i class="fa fa-link"></i>
                                                    {{ lang('Link', 'upload zone') }}
                                                </p> --}}
                                              {{-- MH START --}}
                                                 {{-- @if (subscription()->plan->transfer_password ||
                                                    subscription()->plan->transfer_notify ||
                                                    subscription()->plan->transfer_expiry)
                                                    <div class="drop-down dropzone-form-edit" data-dropdown
                                                        data-dropdown-position="top">
                                                        <div class="drop-down-btn">
                                                            <i class="fa fa-cog"></i>
                                                        </div>
                                                        <div class="drop-down-menu">
                                                            @if (subscription()->plan->transfer_password)
                                                                <a class="drop-down-item dropzone-form-edit-item"
                                                                    data-form-input="password">
                                                                    <i
                                                                        class="fa-solid fa-lock"></i>{{ lang('Password', 'upload zone') }}
                                                                </a>
                                                            @endif
                                                            @if (subscription()->plan->transfer_notify)
                                                                <a class="drop-down-item dropzone-form-edit-item"
                                                                    data-form-input="notifications">
                                                                    <i
                                                                        class="fa-solid fa-bell"></i>{{ lang('Notifications', 'upload zone') }}
                                                                </a>
                                                            @endif
                                                            @if (subscription()->plan->transfer_expiry)
                                                                <a class="drop-down-item dropzone-form-edit-item"
                                                                    data-form-input="date">
                                                                    <i
                                                                        class="fa-solid fa-calendar-days"></i>{{ lang('Expiry Date', 'upload zone') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif --}}
                                              {{-- MH END --}}
                                            </div>
                                            <div class="dropzone-form-body">
                                                <form id="generateLinkForm" data-action="{{ route('transfer.createlink') }}"
                                                    method="GET" class="dropzone-form-inner-item">
                                                    <div class="input-dev">
                                                        {{-- <label
                                                            class="form-label">{{ lang('Your Email Address', 'upload zone') }}
                                                            : <span class="red">*</span></label> --}}
                                                        <input type="email" name="sender_email"
                                                            class="form-control form-control-md"
                                                            placeholder="{{ lang('Your Email Address', 'upload zone') }}">
                                                    </div>
                                                    <div class="mt-3 input-dev">
                                                        {{-- <label
                                                            class="form-label">{{ lang('Subject (optional)', 'upload zone') }}
                                                            :</label> --}}
                                                        <input type="text" name="subject"
                                                            class="form-control form-control-md"
                                                            placeholder="{{ lang('Subject (optional)', 'upload zone') }}">
                                                    </div>
                                                    <div class="mt-3 input-dev">
                                                        {{-- <label
                                                            class="form-label">{{ lang('Custom link (optional)', 'upload zone') }}
                                                            :</label> --}}
                                                        <input type="text" name="custom_link"
                                                            class="form-control form-control-md"
                                                            placeholder="{{ lang('Custom link (optional)', 'upload zone') }}">
                                                    </div>
                                                    @if (subscription()->plan->transfer_password)
                                                        <div class="password d-none mt-3 input-dev">
                                                            <input type="checkbox" name="password_checkbox"
                                                                class="passwordCheck d-none" />
                                                            {{-- <label class="form-label">{{ lang('Password', 'upload zone') }}
                                                                :</label> --}}
                                                            <div class="input-group input-icon input-password mb-3">
                                                                <input type="password" name="password"
                                                                    class="form-control form-control-md"
                                                                    placeholder="{{ lang('Password', 'upload zone') }}">
                                                                <button  class="btn-pass-show">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (subscription()->plan->transfer_notify)
                                                        <div
                                                            class="notifications d-none form-check form-switch form-switch-lg mt-3">
                                                            <input type="checkbox" name="notification_checkbox"
                                                                class="notificationsCheck d-none" />
                                                            <div class="row row-cols-1 g-2 w-100">
                                                                <div class="col d-flex align-items-center">
                                                                    <input id="linkNoti1" type="checkbox"
                                                                        name="download_notify"
                                                                        class="download-notify-input form-check-input">
                                                                    <label class="form-check-label"
                                                                        for="linkNoti1">{{ lang('Notify me when downloaded', 'upload zone') }}</label>
                                                                </div>
                                                                <div class="col d-flex align-items-center">
                                                                    <input id="linkNoti2" type="checkbox"
                                                                        name="expiry_notify"
                                                                        class="download-notify-input form-check-input">
                                                                    <label class="form-check-label"
                                                                        for="linkNoti2">{{ lang('Notify me when expired', 'upload zone') }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if (subscription()->plan->transfer_expiry)
                                                        <div class="date mt-3 d-none mt-3 input-dev">
                                                            <input type="checkbox" name="expiry_checkbox"
                                                                class="dateCheck d-none" />
                                                            <label class="mb-2">{{ lang('Set expiry date', 'upload zone') }}
                                                                :
                                                            </label>
                                                            <input type="datetime-local" name="expiry_at"
                                                                class="form-control form-control-md file-expiry transfer-expiry-date">
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="dropzone-form">
                                        <div class="dropzone-form-header">
                                            {{-- <p class="dropzone-form-title text-white">
                                                <i class="fa fa-envelope"></i>
                                                {{ lang('Email', 'upload zone') }}
                                            </p> --}}
                                            @if (subscription()->plan->transfer_password ||
                                                subscription()->plan->transfer_notify ||
                                                subscription()->plan->transfer_expiry)
                                                <div class="drop-down dropzone-form-edit" data-dropdown
                                                    data-dropdown-position="top">
                                                    <div class="drop-down-btn">
                                                        <i class="fa fa-cog"></i>
                                                        
                                                    </div>
                                                    <div class="drop-down-menu drp">
                                                        @if (subscription()->plan->transfer_password)
                                                            <a class="drop-down-item dropzone-form-edit-item"
                                                                data-form-input="password">
                                                                <i
                                                                    class="fa fa-lock"></i>{{ lang('Password', 'upload zone') }}
                                                            </a>
                                                        @endif
                                                        @if (subscription()->plan->transfer_notify)
                                                            <a class="drop-down-item dropzone-form-edit-item"
                                                                data-form-input="notifications">
                                                                <i
                                                                    class="fa fa-bell"></i>{{ lang('Notifications', 'upload zone') }}
                                                            </a>
                                                        @endif
                                                        @if (subscription()->plan->transfer_expiry)
                                                            <a class="drop-down-item dropzone-form-edit-item"
                                                                data-form-input="date">
                                                                <i
                                                                    class="fa fa-calendar"></i>{{ lang('Expiry Date', 'upload zone') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="dropzone-form-body">
                                            <form id="transferByEmailForm" data-action="{{ route('transfer.sendfiles') }}"
                                                method="POST" class="dropzone-form-inner-item">
                                                <div class="form-block">
                                                    <div class="input-dev">
                                                        {{-- <label class="form-label">{{ lang('Your Email Address', 'upload zone') }}
                                                            : <span class="red">*</span></label> --}}
                                                        <input type="email" name="sender_email"
                                                            class="form-control form-control-md"
                                                            placeholder="{{ lang('Your Email Address', 'upload zone') }}">
                                                    </div>
                                                    {{-- <div class="mt-3 input-dev">
                                               
                                                        <input type="text" name="sender_name"
                                                            class="form-control form-control-md"
                                                            placeholder="{{ lang('Sender name (optional)', 'upload zone') }}">
                                                    </div> --}}
                                                    <div class="input-dev labolabo">
                                                  
                                                        <input type="email" name="send_to" class="form-control form-control-md"
                                                            id="input-tags" placeholder="{{ lang('Send to', 'upload zone') }}">
                                                    </div>
                                                    <div class="mt-3 input-dev">
                                                        {{-- <label class="form-label">{{ lang('Subject (optional)', 'upload zone') }}
                                                            :</label> --}}
                                                        <input type="text" name="subject" class="form-control form-control-md"
                                                            placeholder="{{ lang('Subject (optional)', 'upload zone') }}">
                                                    </div>
                                                    <div class="mt-3  input-dev">
                                                        {{-- <label
                                                            class="form-label">{{ lang('Your message (optional)', 'upload zone') }}
                                                            :</label> --}}
                                                        <textarea name="message" class="form-control form-control-md transfer-textarea"
                                                            placeholder="{{ lang('Your message (optional)', 'upload zone') }}" autosize></textarea>
                                                    </div>
                                                </div>
                                                @if (subscription()->plan->transfer_password)
                                                    <div class="password d-none mt-3 mt-3 input-dev">
                                                        <input type="checkbox" name="password_checkbox"
                                                            class="passwordCheck d-none mt-3 input-dev" />
                                                        {{-- <label class="form-label">{{ lang('Password', 'upload zone') }}
                                                            :</label> --}}
                                                        <div class="input-group input-icon input-password mb-3 mt-3 input-dev">
                                                            <input type="password" name="password"
                                                                class="form-control form-control-md"
                                                                placeholder="{{ lang('Password', 'upload zone') }}">
                                                            <button class="btn-pass-show">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (subscription()->plan->transfer_notify)
                                                    <div
                                                        class="notifications d-none form-check form-switch form-switch-lg mt-3">
                                                        <input type="checkbox" name="notification_checkbox"
                                                            class="notificationsCheck d-none" />
                                                        <div class="row row-cols-1 g-2 w-100" style="margin-left: 0px" >
                                                            <div class="col d-flex align-items-center">
                                                                <input id="emailNoti1" type="checkbox" name="download_notify"
                                                                    class="download-notify-input form-check-input">
                                                                <label class="form-check-label"
                                                                    for="emailNoti1">{{ lang('Notify me when downloaded', 'upload zone') }}</label>
                                                            </div>
                                                            <div class="col d-flex align-items-center">
                                                                <input id="emailNoti2" type="checkbox" name="expiry_notify"
                                                                    class="download-notify-input form-check-input">
                                                                <label class="form-check-label"
                                                                    for="emailNoti2">{{ lang('Notify me when expired', 'upload zone') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (subscription()->plan->transfer_expiry)
                                                    <div class="date mt-3 d-none mt-3 input-dev">
                                                        <input type="checkbox" name="expiry_checkbox"
                                                            class="dateCheck d-none" />
                                                        <label class="mb-2">{{ lang('Set expiry date', 'upload zone') }} :
                                                        </label>
                                                        <input type="datetime-local" name="expiry_at"
                                                            class="form-control form-control-md file-expiry transfer-expiry-date">
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropzone-uploadbox-submit d-flex justify-content-between align-content-center h-100 w-100 ">
                                    <button id="transferFiles" class="btn btn-secondary btn-md  w-auto d-flex justify-content-center align-items-center gap-2"
                                        disabled>
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.4963 1.0591C17.0438 0.737041 17.7524 1.18792 17.6235 1.83204L15.3047 15.7449C15.2403 16.2602 14.6928 16.5501 14.2419 16.3568L10.2484 14.6499L8.18725 17.162C7.73637 17.7095 6.83461 17.4196 6.83461 16.6467V14.038L14.564 4.60173C14.725 4.4085 14.4674 4.18306 14.3064 4.34409L5.0633 12.4921L1.61728 11.0429C1.03757 10.8174 0.973162 9.9801 1.55287 9.65804L16.4963 1.0591Z" fill="white"/>
                                            </svg>
                                            
                                        {{ lang('Transfer', 'upload zone') }}
                                    </button>
                                    <div class="tools-actions d-flex justify-content-between align-items-center gap-2">
                                        <div class="dropzone-reset" data-dz-reset>
                                            {{-- <i class="fa-solid fa-rotate-right fa-2x "></i> --}}
                                            <i class="fa-solid fa-rotate-right fa-2x text-white bg-secondary p-3" style="border-radius: 50%;
                                            background-color: #eff0f24f !important;"></i>
                                            {{-- <p class="p-0 m-0 dropzone-reset-text">{{ lang('Reset', 'upload zone') }}</p> --}}
                                        </div>
                                       
                                    </div>
                                </div>
                                <div class="dropzone-form-submit d-none">
                                    <button
                                        class="btn btn-outline-danger btn-md dropzone-form-cancel">{{ lang('Cancel', 'upload zone') }}</button>
                                    <button
                                        class="btn btn-secondary btn-md dropzone-form-validate">{{ lang('Submit', 'upload zone') }}</button>
                                </div>
                            </div> 
                            
                            <div id="upload-previews">
                                <div class="dz-preview dz-file-preview">
                                    <div class="dz-fileicon">
                                        <span class="vi vi-file" dz-file-extension></span>
                                    </div>
                                    <div class="dz-preview-content">
                                        <div class="dz-details">
                                            <div class="dz-details-info">
                                                <div class="dz-filename">
                                                    <div class="dz-success-mark">
                                                        <span><i class="far fa-check-circle"></i></span>
                                                    </div>
                                                    <div class="dz-error-mark">
                                                        <span><i class="far fa-times-circle"></i></span>
                                                    </div>
                                                    <span data-dz-name></span>
                                                </div>
                                                <div class="dz-meta">
                                                    <div class="dz-size" data-dz-size></div>,
                                                    <div class="dz-percent ms-1" data-dz-percent></div>
                                                </div>
                                            </div>
                                            <a class="dz-remove" data-dz-remove>
                                                <i class="fas fa-times fa-lg"></i>
                                            </a>
                                        </div>
                                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span>
                                        </div>
                                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="dz-dragbox">
                        {{-- <div class="dz-dragbox-inner">
                            <i class="fa-solid fa-arrow-up-from-bracket fa-4x mb-4"></i>
                            <h3 class="mb-3">{{ lang('Drop File Here', 'upload zone') }}</h3>
                            <h4 class="fw-light">
                                {{ lang('Upload your files by drag-and-dropping them on this window', 'upload zone') }}
                            </h4>
                        </div> --}}
                    </div>
                @endif
            </div>
            @if (subscription()->is_subscribed)
                <div class="transfer-completed-card file-container animation-zoomIn d-none">
                    <div class="card-v">
                        <div class="card-v-body">
                            <div class="upload-complete">
                                <div class="upload-complete-icon">
                                    <i class="fas fa-circle-check"></i>
                                </div>
                                <p class="upload-complete-title">{{ lang('Transfer Completed', 'upload zone') }}</p>
                                <p class="upload-complete-text">
                                    {{ lang('Your files have been transferred successfully, here is your download link', 'upload zone') }}.
                                </p>
                                <div class="mt-3">
                                    <div class="form-button">
                                        <input id="linkInput" type="text"
                                            class="transfer-link form-control form-control-md" value="" readonly>
                                        <button class="btn-copy" data-clipboard-target="#linkInput">
                                            <i class="fa-regular fa-clone"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button
                                        class="btn btn-secondary btn-md w-100 new-transfer-btn">{{ lang('New Transfer', 'upload zone') }}</button>
                                    <a href=""
                                        class="btn btn-outline-primary btn-md w-100 mt-3 view-transfer-btn">{{ lang('View Transfer', 'upload zone') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- <h5 class="fs-5 mb-0 p-4 bg-white">{{ lang('Your transfers', 'user') }}</h5> --}}
    <h5 class="recent-transfers fs-5 mb-0 p-4 bg-white">{{ lang('Recent Transfers', 'user') }}</h5>
    
    @include('frontend.user.includes.transfers')
    
    @if (subscription()->is_subscribed)
        @push('config')
            @php
                $maxTransferSizeError = str_replace('{maxTransferSize}', subscription()->plan->transfer_size ?? 0, lang('Max size per transfer : {maxTransferSize}.', 'upload zone'));
                $userRemainingStorageSpace = subscription()->is_subscribed ? subscription()->storage->remaining_space_number : 0;
                $maxTransferSize = subscription()->plan->transfer_size_number;
                $subscribed = subscription()->is_subscribed ? 1 : 0;
                $subscriptionExpired = subscription()->is_expired ? 1 : 0;
                $subscriptionCanceled = subscription()->is_canceled ? 1 : 0;
                $unsubscribedError = !is_null(subscription()->plan->id) ? lang('You have no subscription or your subscription has been expired', 'alerts') : lang('Login or create account to start transferring files', 'alerts');
                $subscriptionCanceledError = lang('Your subscription has been canceled, please contact us for more information', 'alerts');
                $transferPassword = subscription()->plan->transfer_password ? 1 : 0;
                $transferNotify = subscription()->plan->transfer_notify ? 1 : 0;
                $transferExpiry = subscription()->plan->transfer_expiry ? 1 : 0;
            @endphp
            <script>
                "use strict";
                const uploadConfig = {
                    sizesTranslation: ["{{ lang('bytes') }}", "{{ lang('KB') }}", "{{ lang('MB') }}",
                        "{{ lang('GB') }}", "{{ lang('TB') }}"
                    ],
                    sendToTranslation: "{{ lang('Send to', 'upload zone') }}",
                    subscribed: "{{ $subscribed }}",
                    subscriptionExpired: "{{ $subscriptionExpired }}",
                    subscriptionCanceled: "{{ $subscriptionCanceled }}",
                    subscriptionCanceledError: "{{ $subscriptionCanceledError }}",
                    unsubscribedError: "{{ $unsubscribedError }}",
                    userRemainingStorageSpace: "{{ $userRemainingStorageSpace }}",
                    insufficientStorageSpaceError: "{{ lang('Insufficient storage space, please check your space or upgrade your plan', 'alerts') }}",
                    maxTransferSize: "{{ $maxTransferSize }}",
                    maxTransferSizeError: "{{ $maxTransferSizeError }}",
                    transferPassword: "{{ $transferPassword }}",
                    transferNotify: "{{ $transferNotify }}",
                    transferExpiry: "{{ $transferExpiry }}",
                };
                let stringifyUploadConfig = JSON.stringify(uploadConfig),
                    getUploadConfig = JSON.parse(stringifyUploadConfig);
            </script>
            @include('frontend.includes.dropzone-options')
        @endpush
        @push('scripts_libs')
            <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.min.js') }}"></script>
            {{-- <script src="{{ asset('assets/vendor/libs/tags-input/tags-input.min.js') }}"></script> --}}
            <script src="{{ asset('assets/vendor/libs/autosize/autosize.min.js') }}"></script>
        @endpush
        @push('scripts')
            <script src="{{ asset('assets/js/handler.js') }}"></script>
            <script>
                const l = document.querySelector(".tags-input-wrapper input").placeholder = "test test";

            </script>
        @endpush
    @endif
@endsection


