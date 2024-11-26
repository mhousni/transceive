<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('frontend.user.includes.head')
    @include('frontend.user.includes.styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <style>
        div.dt-container .dt-paging .dt-paging-button.current,div.dt-container .dt-paging .dt-paging-button.current:hover {
     color:white !important; 

}
    </style>
</head>

<body>
    <div class="vr__dash vr_user_dash">
        <aside class="vr__dash__sidebar vr_user_dash_sidebar">
            <div class="vr__overlay"></div>
            <div class="vr__dash__sidebar__content">
                <div class="vr__dash__sidebar__header">
                    <a class="logo" href="{{ url('/') }}">
                        {{-- <img src="{{ asset($settings['website_light_logo']) }}" alt="{{ $settings['website_name'] }}" /> --}}
                        <img src="{{ asset('images/logo-white.png') }}" alt="{{ $settings['website_name'] }}" />
                    </a>
                </div>
                <div class="vr__dash__sidebar__body sidebar-mh">
                    <a href="{{ route('user.dashboard') }}" @if (request()->segment(3) == 'dashboard') class="active" @endif>
                        <div class="icon" style="">
                            <img
                                src="{{ asset('images/icons/dash/dashboard.svg') }}"
                                class="header-content-image" width="30"
                            />
                        <img
                            src="{{ asset('images/icons/dash/dashboard-dark.svg') }}"
                            class="header-content-image" width="30"
                        />
                        </div>
                        {{-- <i class="fas fa-th-large fa-lg"></i> --}}
                         {{ lang('Dashboard', 'user') }}
                    </a>
                    <a href="{{ route('user.transfers.index') }}"
                        @if (request()->segment(3) == 'transfers') class="active" @endif>
                        <div class="icon" style="">
                            <img
                                src="{{ asset('images/icons/dash/tranfert.svg') }}"
                                class="header-content-image" width="30"
                            />
                        <img
                            src="{{ asset('images/icons/dash/transfert-dark.svg') }}"
                            class="header-content-image" width="30"
                        />
                        </div>
                        {{ lang('My Transfers', 'user') }}
                    </a>
                    <a href="{{ route('user.subscription') }}" @if (request()->segment(3) == 'subscription') class="active" @endif>
                        <div class="icon" style="">
                            <img
                                src="{{ asset('images/icons/dash/subscription.svg') }}"
                                class="header-content-image" width="30"
                            />
                        <img
                            src="{{ asset('images/icons/dash/subscription-dark.svg') }}"
                            class="header-content-image" width="30"
                        />
                        </div>
                         {{ lang('My Subscription', 'user') }}
                    </a>
                    <a href="{{ route('user.workspace.index') }}" @if (request()->segment(3) == 'workspace') class="active" @endif>
                        <div class="icon" style="">
                            <img
                                src="{{ asset('images/icons/dash/workspace.svg') }}"
                                class="header-content-image" width="30"
                            />
                        <img
                            src="{{ asset('images/icons/dash/workspace-dark.svg') }}"
                            class="header-content-image" width="30"
                        />
                        </div>
                        {{ lang('My Workspace', 'user') }}
                    </a>
                    {{-- @if ($settings['website_tickets_status'])
                        <a href="{{ route('user.tickets') }}" @if (request()->segment(3) == 'tickets') class="active" @endif>
                            <i class="far fa-life-ring fa-lg"></i> {{ lang('My Tickets', 'user') }}
                            @if ($repliedTicketsCount)
                                <span class="vr__counter">{{ $repliedTicketsCount }}</span>
                            @endif
                        </a>
                    @endif --}}
                    {{-- <a href="{{ route('user.settings') }}" @if (request()->segment(3) == 'settings') class="active" @endif>
                        <i class="fa fa-cog fa-lg"></i> {{ lang('Settings', 'user') }}
                    </a> --}}
                </div>
                <div class="vr__dash__sidebar__footer">
                    <form class="d-inline" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-secondary btn-lg w-100">
                            <img
                                src="{{ asset('images/icons/dash/logout.svg') }}"
                                class="header-content-image" width="30"
                            />
                            {{-- <img
                                src="{{ asset('images/icons/dash/logout-dark.svg') }}"
                                class="header-content-image" width="30"
                            /> --}}
                            {{ lang('Logout', 'user') }}
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        <div class="vr__dash__body vr_user_dash_body">
            @if (subscription()->is_canceled)
                <div class="vr__alert bg-danger">
                    <p class="mb-0">
                        <i class="far fa-times-circle me-2"></i>
                        <strong>{{ lang('Your subscription has been canceled, please contact us for more information', 'alerts') }}</strong>
                    </p>
                </div>
            @endif
            @if (!subscription()->plan->is_free && subscription()->is_expired && !subscription()->is_canceled)
                <div class="vr__alert bg-danger">
                    <p class="mb-0">
                        <i class="fas fa-stopwatch me-2"></i>
                        <strong>{{ lang('Your subscription has been expired, Please renew it to continue using the service.', 'user') }}</strong>
                    </p>
                </div>
            @endif
            @if (!subscription()->plan->is_free &&
                subscription()->remining_days < 6 &&
                !subscription()->is_expired &&
                !subscription()->is_canceled)
                <div class="vr__alert bg-warning">
                    <p class="mb-0 text-dark">
                        <i class="fas fa-stopwatch me-2"></i>
                        <strong>{{ lang('Your subscription is about expired, Renew it to avoid deleting your files.', 'user') }}</strong>
                    </p>
                </div>
            @endif
            <nav class="vr__dash__navbar py-15">
                <div class="vr__sidebar__toggle">
                    <i class="fas fa-bars fa-lg"></i>
                </div>
                <a class="logo" href="{{ url('/') }}">
                    <img src="{{ asset($settings['website_favicon']) }}" alt="{{ $settings['website_name'] }}" />
                </a>
                @hasSection('search')
                    <div class="vr__dash__search ms-4 me-4">
                        <div class="vr__dash__search__input">
                            <form action="{{ url()->current() }}" method="GET">
                                <input class="form-control" type="text" name="search"
                                    placeholder="{{ lang('Type to search...', 'user') }}"
                                    @if (request()->input('search')) value="{{ request()->input('search') }}" @endif>
                            </form>
                        </div>
                    </div>
                @endif
                <div class="vr__dash__navbar__actions">
                    <div class="vr__dash__navbar__action">
                        <div class="dropdown vr__language">
                            <button data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="vr__language__icon">
                                    
                                    <img src="{{ asset('images/icons/flags/'.getLang().'.svg') }}" class="" width="30" height="30" alt="">
                                    
                                </div>
                                <div class="vr__language__title text-uppercase">
                                    {{-- {{ getLangName() }} --}}
                                    {{ getLang() }}
                                </div>
                                <div class="vr__language__arrow">
                                    <i class="fas fa-chevron-down fa-xs"></i>
                                </div>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                @foreach ($languages as $language)
                                    <li>
                                        <a class="dropdown-item @if (app()->getLocale() == $language->code) active @endif"
                                            href="{{ langURL($language->code) }}">{{ $language->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
          
                    @hasSection('search')
                        <div class="vr__dash__navbar__action search">
                            <a class="vr__search__button">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    @endif
                    <div class="vr__dash__navbar__action">
                        <div class="dropdown">
                            <a class="noti__btn" data-bs-toggle="dropdown">
                                <i class="far fa-bell"></i>
                                <div class="noti__count">{{ $unreadUserNotifications }}</div>
                            </a>
                            <div class="dropdown-menu noti">
                                <div class="dropdown-menu-header">
                                    <h6 class="mb-0">{{ lang('Notifications', 'user') }}
                                        ({{ $unreadUserNotificationsAll }})</h6>
                                    @if ($unreadUserNotifications)
                                        <a href="{{ route('user.notifications.readall') }}"
                                            class="vr__confirm__action vr__link__color">{{ lang('Make All as Read', 'user') }}</a>
                                    @else
                                        <span class="text-muted">{{ lang('Make All as Read', 'user') }}</span>
                                    @endif
                                </div>
                                <div class="dropdown-menu-body">
                                    @forelse ($userNotifications as $userNotification)
                                        @if ($userNotification->link)
                                            <a class="dropdown-item @if (!$userNotification->status) unread @endif"
                                                href="{{ route('user.notifications.view', hashid($userNotification->id)) }}">
                                            @else
                                                <div
                                                    class="dropdown-item @if (!$userNotification->status) unread @endif">
                                        @endif
                                        <div class="dropdown-item-icon">
                                            <img src="{{ $userNotification->image }}">
                                        </div>
                                        <div class="dropdown-item-info">
                                            <p class="dropdown-item-title">
                                                {{ $userNotification->title }}</p>
                                            <span
                                                class="dropdown-item-text">{{ $userNotification->created_at->diffforhumans() }}</span>
                                        </div>
                                        @if ($userNotification->link)
                                            </a>
                                        @else
                                </div>
                                @endif
                            @empty
                                <div class="empty text-center">
                                    <small class="text-muted mb-0">{{ lang('No notifications found', 'user') }}</small>
                                </div>
                                @endforelse
                            </div>
                            <div class="dropdown-menu-footer">
                                <a class="dropdown-item" href="{{ route('user.notifications') }}">
                                    {{ lang('View All', 'user') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="vr__dash__navbar__action">
                    <div class="dropdown">
                        <div class="vr__dash__navbar__user" data-bs-toggle="dropdown">
                            <img src="{{ asset(userAuthinfo()->avatar) }}"
                                alt="{{ userAuthinfo()->firstname . ' ' . userAuthinfo()->lastname }}" width="50" height="50" />
                                <div class="user-box">
                                    <h6 class="text-black "><strong>{{ userAuthinfo()->firstname . ' ' . userAuthinfo()->lastname }}</strong></h6>
                                    {{-- <h6 class="text-black ">{{ userAuthinfo()->firstname . ' ' . userAuthinfo()->lastname }}</h6> --}}
                                </div>
                        </div>
                        <ul class="dropdown-menu dash-drop-auth" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('user.settings') }}">
                                {{-- <i class="fa fa-edit"></i> --}}
                                        <img src="{{ asset('images/icons/dash/setting.svg') }}" class="" width="30" height="30" alt="">
                                        {{ lang('Account Details', 'user') }}</a>
                            </li>
                            <li><a class="dropdown-item d-flex gap-2 align-items-center" href="{{ route('user.settings.password') }}">
                                <img src="{{ asset('images/icons/dash/lock.svg') }}" class="" width="30" height="30" alt="">
                                {{ lang('Change Password', 'user') }}</a></li>
                            {{-- <li><a class="dropdown-item" href="{{ route('user.settings.2fa') }}"><i
                                        class="fas fa-fingerprint"></i>{{ lang('2FA Authentication', 'user') }}</a>
                            </li> --}}
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item   d-flex gap-2 align-items-center">
                                        <img src="{{ asset('images/icons/dash/logout.svg') }}" class="" width="30" height="30" alt="">{{ lang('Logout', 'user') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
        </div>
        </nav>
        <div class="vr__dash__body__content ">
            <div class="vr__dash__container">
                <div class="vr__dash__title mb-4">
                    <div class="row justify-content-between align-items-center g-3">
                        <div class="col-auto">
                            {{-- <h5 class="fs-4 mb-2">@yield('title')</h5> --}}
                            @include('frontend.user.includes.breadcrumb')
                        </div>
                        <div class="col-auto">
                            @hasSection('status_dropdown')
                                <div class="dropdown me-2 d-inline">
                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ lang('Status', 'tickets') }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item"
                                                href="{{ route('user.tickets') }}">{{ lang('All', 'tickets') }}</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('user.tickets.status', 'opened') }}">{{ lang('Opened', 'tickets') }}</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('user.tickets.status', 'answered') }}">{{ lang('Answered', 'tickets') }}</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('user.tickets.status', 'replied') }}">{{ lang('Replied', 'tickets') }}</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('user.tickets.status', 'closed') }}">{{ lang('Closed', 'tickets') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            @hasSection('back')
                                <a href="@yield('back')" class="btn btn-primary me-2"><i
                                        class="fas fa-arrow-left me-2"></i>{{ lang('Back', 'user') }}</a>
                            @endif
                            @hasSection('link')
                                <a href="@yield('link')" class="btn btn-secondary me-2"><i
                                        class="fa fa-plus"></i></a>
                            @endif
                            @if (request()->routeIs('user.transfers.show') && !isExpiry($transfer->expiry_at) && $transfer->status)
                                <button class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#transferSettingsModal"><i
                                        class="fa fa-cog me-2"></i>{{ lang('Transfer settings', 'user') }}</button>
                            @endif
                            {{-- @if (request()->routeIs('user.subscription') || request()->routeIs('user.transfers.index'))
                                <a href="{{ url('/') }}" class="btn btn-primary"><i
                                        class="far fa-paper-plane me-2"></i>{{ lang('Start Transfer', 'user') }}</a>
                            @endif --}}
                            @if (request()->routeIs('user.notifications'))
                                @if ($unreadUserNotifications)
                                    <a class="vr__confirm__action btn btn-outline-success"
                                        href="{{ route('user.notifications.readall') }}">{{ lang('Make All as Read', 'user') }}</a>
                                @else
                                    <button class="btn btn-outline-success"
                                        disabled>{{ lang('Make All as Read', 'user') }}</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
        <div class="vr__dash__footer mt-auto">
            <div class="row justify-content-between">
                <div class="col-auto">
                    <p class="mb-0">&copy; <span data-year></span> {{ $settings['website_name'] }} -
                        {{ lang('All rights reserved') }}.</p>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    @include('frontend.configurations.config')
    @include('frontend.configurations.widgets')
    @include('frontend.user.includes.scripts')
    <script>$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });</script>
   <script>
    console.log("log me");


    document.querySelectorAll('.btn-pass-show').forEach((el)=> {
    el.addEventListener("click", (e) => {
        e.preventDefault();
        let x = e.target.parentNode.querySelector("input")
        //  let x = document.querySelector('.input-password input');
        // console.log("ooooo");
        
        
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }



   });
   })

 
const msjAlert  = (msj) => {
        document.querySelector(".popup-bg").classList.remove("d-none");
        document.querySelector(".messagebody").innerHTML = msj;;
        document.querySelector(".messagePopup").classList.remove("d-none");
        document.querySelector(".messagePopup i").classList.add("text-warning");
        document.querySelector(".messagePopup .messagebody").classList.add("text-warning");
}


const btnActionsWorkspace = document.querySelectorAll(".btn-actions-workspace");

btnActionsWorkspace.forEach(element => {
    
    element.addEventListener('click', (e) => {
        
        console.log(e.target.parentNode.querySelector(".popup-actions"));
        
        e.currentTarget.parentNode.querySelector(".popup-actions").classList.toggle("active");
        
    })
    
});

const btnStarStarred = document.querySelectorAll(".btn-star");
btnStarStarred.forEach(element => {
    
    element.addEventListener('click', (e) => {
        let unique_id = e.currentTarget.dataset.idtransfer;
        const loader = element.parentNode.querySelector(".loader");
        loader.classList.remove("d-none");
        
        let link = "{{ route('user.workspace.setStarredFiles' ) }}";
       
        console.log(link);
        
        $.ajax({
        url: link + "?&unique_id=" + unique_id,
        type: 'POST',
        dataType: "json",
        data: {
            "unique_id" : unique_id
        },
        success: function(data) {
            // log response into console
            console.log(data);
            console.log("okay by" + e.target);
            loader.classList.add("d-none");
            e.target.parentNode.classList.toggle("btn-star-starred");
        }
    });
        
    })
    
});

/* RENAME FILE */
const popBtnRename = document.querySelectorAll(".pop-btn-rename");
popBtnRename.forEach((el) => {
    el.addEventListener("click" , (e) => {
    
    let unique_id = e.currentTarget.dataset.idtransfer;
    console.log(unique_id);
    document.querySelector(".renamePopup").setAttribute("data-idtransfer",unique_id);
    document.querySelector(".renamePopup").classList.remove("d-none");
    document.querySelector(".popup-bg").classList.remove("d-none");
})
})
const btnCancelRenameFile = document.querySelector('.btn-cancel-rename-file');
btnCancelRenameFile.addEventListener("click" , () => {
    console.log("cancel");
    document.querySelector(".renamePopup").classList.add("d-none");
    document.querySelector(".popup-bg").classList.add("d-none");
    
})

const btnRenameFile = document.querySelector('.btn-rename-file');
btnRenameFile.addEventListener("click" , (e) => {
    console.log("rename");
    let unique_id = document.querySelector(".renamePopup").getAttribute("data-idtransfer");
    let newName = document.querySelector(".newname").value;
    const loader = btnRenameFile.parentNode.querySelector(".loader");
    loader.classList.remove("d-none");
        
        let link = "{{ route('user.workspace.renameFile' ) }}";
       
        console.log(link);
        
        $.ajax({
        url: link + "?&unique_id=" + unique_id,
        type: 'POST',
        dataType: "json",
        data: {
            "unique_id" : unique_id,
            "new_name" : newName,
        },
        success: function(data) {
            loader.classList.add("d-none");
            msjAlert("the File is successfully renamed")
            setTimeout(() => {
                location.reload();
            }, 1000);
            //e.target.parentNode.classList.toggle("btn-star-starred");
        }})
    // document.querySelector(".renamePopup").classList.add("d-none");
    // document.querySelector(".popup-bg").classList.add("d-none");
})
/* RENAME FILE END*/

/* DELETE FILE */
const popBtnArchive = document.querySelectorAll(".pop-btn-archive");
popBtnArchive.forEach((el) => {
    el.addEventListener("click" , (e) => {
    
    let unique_id = e.currentTarget.dataset.idtransfer;
    console.log(unique_id);
    document.querySelector(".archivePopup").setAttribute("data-idtransfer",unique_id);
    document.querySelector(".archivePopup").classList.remove("d-none");
    document.querySelector(".popup-bg").classList.remove("d-none");
})
})
const btnCancelArchiveFile = document.querySelector('.btn-cancel-archive-file');
btnCancelArchiveFile.addEventListener("click" , () => {
    console.log("cancel");
    document.querySelector(".archivePopup").classList.add("d-none");
    document.querySelector(".popup-bg").classList.add("d-none");
})

const btnArchiveFile = document.querySelector('.btn-archive-file');
    btnArchiveFile.addEventListener("click" , (e) => {
    console.log("acrhive");
    let unique_id = document.querySelector(".archivePopup").getAttribute("data-idtransfer");
    //let newName = document.querySelector(".newname").value;
    const loader = btnArchiveFile.parentNode.querySelector(".loader");
    loader.classList.remove("d-none");
        
        let link = "{{ route('user.workspace.archiveFile' ) }}";
       
        console.log(link);
        
        $.ajax({
        url: link + "?&unique_id=" + unique_id,
        type: 'POST',
        dataType: "json",
        data: {
            "unique_id" : unique_id,
        },
        success: function(data) {
            // log response into console
            loader.classList.add("d-none");

            msjAlert("The file was successfully archived")
            setTimeout(() => {
                location.reload();
            }, 1000);


            
            //e.target.parentNode.classList.toggle("btn-star-starred");
        }})
    // document.querySelector(".renamePopup").classList.add("d-none");
    // document.querySelector(".popup-bg").classList.add("d-none");
})
/* DELETE FILE END*/


/* DELETE SELECTED FILE */
const btnArchiveAll = document.querySelector(".btn-archive-all");
    
    btnArchiveAll.addEventListener("click" , (e) => {
        const selectedArchivedList = document.querySelectorAll(".btn-checkx-active");
        console.log(selectedArchivedList.length );
        const ids = [];
        if(selectedArchivedList.length > 0) {
            selectedArchivedList.forEach((el) => {
               ids.push(el.getAttribute("data-idtransfer"));
               
                
            })
            console.log(ids);
            let link = "{{ route('user.workspace.archiveFiles' ) }}";
       
        console.log(link);
        
        $.ajax({
        url: link,
        type: 'POST',
        dataType: "json",
        data: {
            "ids" : ids,
        },
        success: function(data) {
            // log response into console
            console.log(data);
            console.log("okay by" + e.target);
            // location.reload();
            //e.target.parentNode.classList.toggle("btn-star-starred");
        }})
            
        }else {
            console.log("Please select un element a supprimer");
            document.querySelector(".popup-bg").classList.remove("d-none");
            document.querySelector(".messagebody").innerHTML = "Please select at least one to delete";
            document.querySelector(".messagePopup").classList.remove("d-none");
            document.querySelector(".messagePopup i").classList.add("text-warning");
            document.querySelector(".messagePopup .messagebody").classList.add("text-warning");
            
        }
        

    })
    

/* DELETE FILE END*/

const popBtnClose = document.querySelectorAll(".btn-close");
popBtnClose.forEach((el) => {
    el.addEventListener("click" , (e) => {
    
    let unique_id = e.currentTarget.dataset.idtransfer;
    console.log(unique_id);
    e.currentTarget.classList.toggle("btn-checkx-active");
    document.querySelector(".sharePopup").classList.add("d-none");
    document.querySelector(".renamePopup").classList.add("d-none");
    document.querySelector(".archivePopup").classList.add("d-none");
    document.querySelector(".movePopup").classList.add("d-none");
    document.querySelector(".popup-bg").classList.add("d-none");

})
})


/* SHARE FILE */
const popBtnCheck = document.querySelectorAll(".btn-checkx");
popBtnCheck.forEach((el) => {
    el.addEventListener("click" , (e) => {
    
    let unique_id = e.currentTarget.dataset.idtransfer;
    console.log(unique_id);
    e.currentTarget.classList.toggle("btn-checkx-active");
 
})
})




const popBtnShare = document.querySelectorAll(".pop-btn-share");
popBtnShare.forEach((el) => {
    el.addEventListener("click" , (e) => {
    
    let unique_id = e.currentTarget.dataset.idtransfer;
    console.log(unique_id);
    document.querySelector(".sharePopup").setAttribute("data-idtransfer",unique_id);
    document.querySelector(".sharePopup").classList.remove("d-none");
    document.querySelector(".popup-bg").classList.remove("d-none");
})
})
const btnCancelShareFile = document.querySelector('.btn-copy-share-file');
btnCancelShareFile.addEventListener("click" , () => {
    console.log("cancel");
    document.querySelector(".sharePopup").classList.add("d-none");
    document.querySelector(".popup-bg").classList.add("d-none");
})

// tagInput.placeholder = "OK";
const btnShareFile = document.querySelector('.btn-share-file');
    btnShareFile.addEventListener("click" , (e) => {
    console.log("share");
    let unique_id = document.querySelector(".sharePopup").getAttribute("data-idtransfer");
    //let newName = document.querySelector(".newname").value;
    const loader = btnRenameFile.parentNode.querySelector(".loader");
    loader.classList.remove("d-none");
        
        let link = "{{ route('user.workspace.shareFile' ) }}";
        // let emails = document.querySelector(".emails").value;
        // let message = document.querySelector(".message").value;
        // let isPublic = document.querySelector(".ispublic").value;
        // let trExpire = document.querySelector(".dateex").value;
        // let withPass = document.querySelector(".withpass").value;

        // let emails = document.querySelector(".emails").value;
        let message = document.querySelector(".message").value;
        const mhIsPublic = document.getElementById("mhIsPublic").value ;
        const mhCanModify = document.getElementById("mhCanModify").value;
        const mhWithPass = document.getElementById("mhWithPass").value;
        const mhDateEx = document.getElementById("mhDateEx").value;        
        const emails = document.getElementById("input-tags").value;

        //return;
        $.ajax({
        url: link + "?&unique_id=" + unique_id,
        type: 'POST',
        dataType: "json",
        data: {
            "unique_id" : unique_id,
            "emails" : emails,
            "message" : message,
            "mhIsPublic" : mhIsPublic,
            "mhCanModify" : mhCanModify,
            "mhWithPass" : mhWithPass,
            "mhDateEx" : mhDateEx,
        },
        success: function(data) {
            // log response into console
            loader.classList.add("d-none");
            msjAlert("The files was successfully shared");
            setTimeout(() => {
              //  location.reload();
            },1000)
           // 
            //e.target.parentNode.classList.toggle("btn-star-starred");
        }})
    // document.querySelector(".renamePopup").classList.add("d-none");
    // document.querySelector(".popup-bg").classList.add("d-none");
})

const btnConfShare = document.querySelector(".btn-back-conf");
btnConfShare.addEventListener("click", () => {
    const boxLayout =  document.querySelector(".box-layout");
    boxLayout.classList.remove("d-none");
});
const btnBackShare = document.querySelector(".btn-back-share");
btnBackShare.addEventListener("click", () => {
    const boxLayout =  document.querySelector(".box-layout");
    boxLayout.classList.add("d-none");
});




const btnApplyParamsShareFile = document.querySelector(".btn-apply-params-share-file");

btnApplyParamsShareFile.addEventListener("click", () => {
    let a = document.querySelector('input[name="grant_people"]:checked').value;
    let b = document.getElementById('canModify');
    b = b.options[b.selectedIndex].value;
    // .options[e.selectedIndex].value;
    let c = document.getElementById('setPass').value;
    let d = document.getElementById('defindateex').value;

    const mhIsPublic = document.getElementById("mhIsPublic").value = a;
    const mhCanModify = document.getElementById("mhCanModify").value = b;
    const mhWithPass = document.getElementById("mhWithPass").value = c;
    const mhDateEx = document.getElementById("mhDateEx").value = d;
    console.log("a : " + a);
    console.log("b : " + b);
    console.log("c : " + c);
    console.log("d : " + d);
    const boxLayout =  document.querySelector(".box-layout");
    boxLayout.classList.add("d-none");
});

/* SHARE FILE END*/


/* BTN MOVE */

const popBtnCheckMove = document.querySelectorAll(".btn-checky");
popBtnCheckMove.forEach((el) => {
    el.addEventListener("click" , (e) => {
    
    let unique_id = e.currentTarget.dataset.idtransfer;
    console.log(unique_id);
    e.currentTarget.classList.toggle("btn-checky-active");
 
})
})


const popBtnMove = document.querySelectorAll(".pop-btn-move");
popBtnMove.forEach((el) => {
    el.addEventListener("click" , (e) => {
    
    let unique_id = e.currentTarget.dataset.idtransfer;
    console.log(unique_id);
    document.querySelector(".movePopup").setAttribute("data-idtransfer",unique_id);
    document.querySelector(".movePopup").classList.remove("d-none");
    document.querySelector(".popup-bg").classList.remove("d-none");
})
})

const btnCreateFolderPopup = document.querySelectorAll(".btn-create-folder-popup");

btnCreateFolderPopup.forEach((btn) => {
    btn.addEventListener("click" , (e) => {
        document.querySelector(".newFolderPopup").classList.remove("d-none");
        document.querySelector(".popup-bg").classList.add("d-none");
        document.querySelector(".popup-bg").classList.remove("d-none");
    });
})

const btnCreateFolder = document.querySelector('.btn-create-folder');
    btnCreateFolder.addEventListener("click" , (e) => {

        // let unique_id = document.querySelector(".sharePopup").getAttribute("data-idtransfer");
        let link = "{{ route('user.workspace.createFolder' ) }}";
        const folder_name = document.querySelector(".folder_name").value;

        

        $.ajax({
        url: link,
        type: 'POST',
        dataType: "json",
        data: {
            "folder_name" : folder_name,
            "ids" : ids,
            // "emails" : emails,
            // "message" : message,
            // "mhIsPublic" : mhIsPublic,
            // "mhCanModify" : mhCanModify,
            // "mhWithPass" : mhWithPass,
            // "mhDateEx" : mhDateEx,
        },
        success: function(data) {
            // log response into console
            console.log(data);
            console.log("okay by" + e.target);
           location.reload();
            //e.target.parentNode.classList.toggle("btn-star-starred");
        }})
})

const btnMoveHere = document.querySelector(".btn-move-file");
btnMoveHere.addEventListener("click" , (e) => {
    const selectedToMoveList = document.querySelectorAll(".btn-checky-active");
    console.log(selectedToMoveList.length );
    if(selectedToMoveList.length > 0) {
        document.querySelector(".moveHerePopup").classList.remove("d-none");
        document.querySelector(".popup-bg").classList.add("d-none");
        document.querySelector(".popup-bg").classList.remove("d-none");
    }else {
        console.log("Please select at least one")
        document.querySelector(".popup-bg").classList.remove("d-none");
        document.querySelector(".messagebody").innerHTML = "Please select at least one";
        document.querySelector(".messagePopup").classList.remove("d-none");
        document.querySelector(".messagePopup i").classList.add("text-warning");
        document.querySelector(".messagePopup .messagebody").classList.add("text-warning");
    }
        
});

const btnInfoHide = document.querySelector(".btn-info-hide");
btnInfoHide.addEventListener("click" , (e) => {
    document.querySelector(".popup-bg").classList.add("d-none");
    document.querySelector(".messagePopup").classList.add("d-none");
        
});

const selectedFolders = document.querySelectorAll(".moveHerePopup ul li");
selectedFolders.forEach((btn) => {
    btn.addEventListener("click" , (e) => {
       
        console.log( e.target.getAttribute("data-idfile"));
        const folder_id = e.target.getAttribute("data-idfile");

        const selectedToMoveList = document.querySelectorAll(".btn-checky-active");
        console.log(selectedToMoveList.length );
        const ids = [];
        if(selectedToMoveList.length > 0) {
            selectedToMoveList.forEach((el) => {
               ids.push(el.getAttribute("data-idtransfer"));  
            })
            console.log(ids);
        }

        let link = "{{ route('user.workspace.moveFileToFolder' ) }}";

        $.ajax({
        url: link,
        type: 'POST',
        dataType: "json",
        data: {
            "folder_id" : folder_id,
            "ids" : ids,
            // "emails" : emails,
            // "message" : message,
            // "mhIsPublic" : mhIsPublic,
            // "mhCanModify" : mhCanModify,
            // "mhWithPass" : mhWithPass,
            // "mhDateEx" : mhDateEx,
        },
        success: function(data) {
            // log response into console
            console.log(data);
            console.log("okay by" + e.target);
           location.reload();
            //e.target.parentNode.classList.toggle("btn-star-starred");
        }})





    });
})


/* END MOVE BTN */



   </script>
   <script src="https://transceive.ca/assets/vendor/libs/tags-input/tags-input.min.js"></script>
   <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
   <script>
    // let table = new DataTable('#myTable');
    $('#myTable').DataTable( {
        // searching: false,
        lengthChange: false,
         bInfo :false
} );

   const search2 = document.querySelector('.search-2');
   search2.addEventListener( 'change', (e) => {
        document.getElementById("dt-search-0").value = e.target.value;
        var event = new Event('change');
        // var focus = new Event('focus');
        document.getElementById("dt-search-0").focus();
        // document.getElementById("dt-search-0").dispatchEvent(focus);
        
   })



//    document.querySelectorAll('.labolabo .tags-input-wrapper').forEach(
//     (el, index) => {
//         if (index > 1){
//             el.remove()
//         }
//     }
//    );
   </script>
</body>

</html>
