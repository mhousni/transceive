@extends('frontend.user.layouts.dash')
@section('title', lang('My Workspace', 'user'))
@section('search', true)
@section('content')
    <div class="row">
        <div class="row ">
            <div class="col-lg-3 wok d-flex flex-column justify-content-between">
                <div class="header-wok">
                    <h2 class="fw-bold text-main">{{ lang('Files', 'user') }}</h2>
                <ul class="list-unstyled workspace-list">
                    <li class="text-main bg-white p-2">
                        <a href="{{ route('user.workspace.index' ) }}">
                            <img
                            src="{{ asset('images/icons/dash/starred.svg') }}"
                            class="header-content-image"
                            />    
                            {{ lang('All Files', 'user') }}
                            <span>{{($activeTransfersCount)}}</span>
                        </a>
                    </li>
                    
                    <li class="text-main bg-white p-2">
                        <a href="?archived=true">
                            <img
                            src="{{ asset('images/icons/dash/filed.svg') }}"
                            class="header-content-image"
                            />    
                            {{ lang('Bin', 'user') }}
                            <span>{{$archivedTransfersCount}}</span>
                        </a>
                    </li>
                    <li class="text-main bg-white p-2">
                        <a href="?starred=true">
                            <img
                            src="{{ asset('images/icons/dash/shared.svg') }}"
                            class="header-content-image"
                            />    
                            {{ lang('Starred', 'user') }}
                            <span>{{$starredTransfersCount}}</span>
                        </a>
                    </li>
                    <li class="text-main bg-white p-2">
                        <a href="?shared=true">
                            <img
                            src="{{ asset('images/icons/dash/deleted.svg') }}"
                            class="header-content-image"
                            />    
                            {{ lang('Shared', 'user') }}
                            {{-- <span>156</span> --}}
                        </a>
                    </li>
                </ul>
                </div>
                <button class="btn-create-folder-popup btn p-2 bg-dashboard text-white">
                    {{ lang('Create new folder', 'user') }}
                </button>
            </div>
            <div class="col-lg-9">
                <div class="vr__dash__table">
                    <div class="vr__table">
                        <div class="vr__dash__search  ms-4 me-4 p-2">
                                    <div class="search-header d-flex justify-content-between align-items-center">
                                        <div class="vr__dash__search__input d-flex ">
                                            <form action="http://127.0.0.1:8000/en/user/transfers" method="GET"></form>
                                                <input class="form-control search-2" type="text" name="search" placeholder="Type to search...">
                                                   <button class="btn " style="margin-left: -40px"> <i class="fa fa-search fa-x"></i></button>
                                            
                                        </div>
                                        <button class="btn btn-archive-all">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                        <table id="myTable"  >
                            
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center"></th>
                                    <th class="text-center">{{ lang('Name', 'user') }}</th>
                                    <th class="text-center">{{ lang('Modified', 'user') }}</th>
                                    <th class="text-center">{{ lang('File size', 'user') }}</th>
                                    <th class="text-center">{{ lang('Sharing', 'user') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{dump($transfers)}} --}}
                                @foreach ($transfers as $transfer)
                                 <tr>
                                    <td>
                                        <button class="btn-checkx  b-color border-1  " data-idtransfer="{{$transfer->id}}"><i class="fa fa-check"></i></button>
                                    </td>
                                    <td>
                                        <button class="btn-star @if ($transfer->starred) btn-star-starred @endif  border-0 p-2 bg-x text-light" data-idtransfer="{{$transfer->id}}"><i class="fa fa-star" aria-hidden="true"></i>
                                        </button>
                                        <div class="loader loader-starred d-none"></div>
                                    </td>
                                    {{-- <td>
                                        {{ $transfer->link }}
                                    </td> --}}
                                    <td style="max-width: 200px; overflow: hidden;">
                                        {{-- @foreach ($transfer->transferFiles as $transferFile) --}}
                                        {{$transfer->name}}
                                        {{-- @endforeach --}}
                                        
                                    </td>
                                    <td>
                                        {{ vDate($transfer->updated_at) }}
                                    </td>
                                    <td>
                                        {{-- @foreach ($transfer->transferFiles as $transferFile) --}}
                                        {{$transfer->size}}
                                        {{-- @endforeach --}}
                                       
                                    
                                    </td>
                                    <td>
                                        @if ($transfer->is_public == 0)
                                        {{ lang('Private', 'user') }} 
                                        @else 
                                        {{ lang('Public', 'user') }} 
                                        @endif
                                        
                                    </td>
                                    <td>
                                        <button class="border-0 bg-white btn-actions-workspace"><i class="fa fa-ellipsis-v"></i></button>
                                        <div class="popup-actions position-relative">
                                            <ul class="d-flex flex-column gap-1 list-unstyled bg-white">
                                                <li class="bg-white ">
                                                    <a class="p-2 d-flex gap-1 align-content-center pop-btn-share" data-idtransfer="{{$transfer->id}}" data-link="{{$transfer->path}}">
                                                        <img src="{{ asset('images/icons/dash/share-me.svg') }}" alt=""> {{ lang('Share', 'user') }} </a>
                                                    
                                                </li>
                                                
                                                <li class="bg-white "><a class="p-2 d-flex gap-1 align-content-center pop-btn-archive" data-idtransfer="{{$transfer->id}}"><img src="{{ asset('images/icons/dash/delete-me.svg') }}" alt=""> {{ lang('Delete', 'user') }}</a></li>
                                                <li class="bg-white "><a class="p-2 d-flex gap-1 align-content-center pop-btn-move" data-idtransfer="{{$transfer->id}}"><img src="{{ asset('images/icons/dash/move-me.svg') }}" alt=""> {{ lang('Move', 'user') }}</a></li>
                                                <li class="bg-white "><a class="p-2 d-flex gap-1 align-content-center pop-btn-download" href="{{ route('transfer.download.index', $transfer->transfer->link) }}" target="_blank" ><img src="{{ asset('images/icons/dash/getlink.svg') }}" alt="">{{ lang('Download', 'user') }}</a></li>
                                                <li class="bg-white "><a class="p-2 d-flex gap-1 align-content-center pop-btn-rename" data-idtransfer="{{$transfer->id}}" ><img src="{{ asset('images/icons/dash/rename-me.svg') }}" alt="">{{ lang('Rename', 'user') }}</a></li>
                                            </ul>
                                        </div>
                                    
                                    </td>
                                 </tr>
                                @endforeach
                            </tbody>    
                        </table>
                        <div class="popup-bg d-none"></div>
                        <div class="renamePopup d-none d-flex flex-column gap-3 p-3" data-idtransfer="0">
                            
                            <div class="box-header d-flex justify-content-between">
                                <h2>{{ lang('Rename', 'user') }}</h2>
                                <div class="btn close">
                                    <button class="btn btn-close">
                                        
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <input type="text" class="newname" placeholder="Name" class="p-2 border-0 bg-black-sub">
                            </div>
                            <div class="box-footer d-flex justify-content-end gap-2">
                                    <button class="btn-rename-file btn p-2 bg-dashboard text-white">
                                        {{ lang('Rename', 'user') }}
                                    </button>
                                    <div class="loader loader-starred d-none"></div>
                                    <button class="btn-cancel-rename-file btn ">
                                        {{ lang('Cancel', 'user') }}
                                    </button>
                            </div>
                        </div>
                        <div class="archivePopup d-none d-flex flex-column gap-3 p-3" data-idtransfer="0">
                            
                            <div class="box-header d-flex justify-content-between">
                                <h2 class="fw-bold">{{ lang('Delete', 'user') }}?</h2>
                                <div class="btn close">
                                    <button class="btn btn-close">
                                        
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <p>{{ lang('Are you sure you want to send this item to the bin?', 'user') }}</p>
                            </div>
                            <div class="box-footer d-flex justify-content-end gap-2">
                                    <button class="btn-archive-file btn p-2 bg-dashboard text-white">
                                        {{ lang('Delete', 'user') }}
                                    </button>
                                    <div class="loader loader-starred d-none"></div>
                                    <button class="btn-cancel-archive-file btn ">
                                        {{ lang('Cancel', 'user') }}
                                    </button>
                            </div>
                        </div>
                        <div class="sharePopup d-none d-flex flex-column gap-3 p-3 " data-idtransfer="0">
                            <div class="box-layout p-2 d-none" >
                                <div class="box-layout-header p-2 d-flex justify-content-between">
                                    <h3 class="d-flex gap-1 align-items-center"> 
                                        <button class="btn btn-back-share">
                                        <img src="{{ asset('images/icons/dash/arrow.svg') }}" alt="">
                                    </button>Link Parameters</h3>
                                    <div class="btns d-flex gap-1 align-items-center">
                                        <button class="btn btn-close">
                                            {{-- <img src="{{ asset('images/icons/dash/x.svg') }}" alt=""> --}}
                                        </button>
                                    </div>
                                </div>
                                <div class="box-layout-body">
                                    <div class="box-layout-field d-flex justify-content-between align-items-center">
                                        <label for="everybody">{{ lang('Everybody', 'user') }}</label>
                                        <input type="radio" class="radio-input" id="html" checked name="grant_people" value="1">
                                      
                                    </div>
                                    <div class="box-layout-field d-flex justify-content-between align-items-center">
                                        <label for="people">{{ lang('People of Your Choice', 'user') }}</label>
                                        <input type="radio" class="radio-input" id="html" name="grant_people" value="0">
                                      
                                    </div>
                                    <div class="box-layout-field d-flex justify-content-between align-items-center">
                                        <select name="cam" class="" id="canModify">
                                            <option value="1">{{ lang('YES', 'user') }}</option>
                                            <option value="0">{{ lang('NO', 'user') }}</option>
                                            {{-- <option value="1">test</option> --}}
                                        </select>
                                      
                                    </div>
                                    <div class="box-layout-field d-flex justify-content-between align-items-center">
                                        {{-- <label for="people">People of Your Choice</label> --}}
                                        <input type="text" placeholder="Set the Password"  id="setPass" name="setpass" >
                                      
                                    </div>
                                    <div class="box-layout-field d-flex justify-content-between align-items-center">
                                        {{-- <label for="people">People of Your Choice</label> --}}
                                        <input type="datetime-local"  placeholder="{{ lang('Define the expiration date (DD/MM/YYYY)', 'user') }}" id="defindateex" name="defindateex" >
                                      
                                    </div>
                                   
                                </div>
                                <div class="box-layout-footer d-flex justify-content-end gap-2 p-2">
                                    
                                    <button class="btn-apply-params-share-file btn bg-dashboard text-white">
                                        {{ lang('Apply', 'user') }}
                                    </button>
                                    <button class="btn-cancel-params-share-file btn">
                                        {{ lang('Cancel', 'user') }}
                                    </button>
                            </div>
                            </div>
                            <div class="box-header d-flex justify-content-between">
                                <h3>{{ lang('Share Element', 'user') }}</h3>
                                <div class="btns d-flex gap-1 align-items-center">
                                    <button class="btn btn-back-conf " >
                                        <img src="{{ asset('images/icons/dash/conf.svg') }}" alt="">
                                    </button>
                                    <button class="btn btn-close">
                                        {{-- <img src="{{ asset('images/icons/dash/x.svg') }}" alt=""> --}}
                                    </button>
                                    
                                    {{-- <div class="btn close" style="width:30px">
                                       
                                    </div>
                                    <div class="btn conf" style="width:30px">
                                        
                                    </div> --}}
                                </div>
                            </div>
                            <div class="box-body d-flex flex-column gap-1">
                                <input type="email" name="send_to" class="form-control form-control-md emails"
                                id="input-tags" placeholder="{{ lang('Send to', 'upload zone') }}" />
                                {{-- <input type="text" class="newname emails"data-role="tagsinput" id="mhemails" name="emails" placeholder="Emails" class="p-3 border-0 bg-black-sub"> --}}
                                <input type="text" class="newname ispublic d-none" id="mhIsPublic" name="ispublic" placeholder="{{ lang('Name', 'user') }}" class="p-3 border-0 bg-black-sub">
                                <input type="text" class="newname mhCanModify d-none" id="mhCanModify" name="mhCanModify"  placeholder="{{ lang('Can Modify', 'user') }}" class="p-3 border-0 bg-black-sub">
                                <input type="text" class="newname dateex d-none" id="mhDateEx" name="dateex" placeholder="{{ lang('Can Modify', 'user') }}" class="p-3 border-0 bg-black-sub">
                                <input type="text" class="newname withpass d-none" id="mhWithPass" name="withpass" placeholder="{{ lang('Set The Password', 'user') }}" class="p-3 border-0 bg-black-sub">
                                <textarea name="message" class="message" placeholder="Add a message" id="mhMessage" cols="15" rows="5"></textarea>
                            </div>
                            <div class="box-footer d-flex justify-content-end gap-2">
                                    
                                    <button class="btn-copy-share-file btn ">
                                        {{ lang('Copy The Link', 'upload zone') }} 
                                    </button>
                                    <button class="btn-share-file btn p-2 bg-dashboard text-white">
                                        {{ lang('Send', 'upload zone') }}
                                    </button>
                            </div>
                        </div>

                        <div class="movePopup d-none d-flex flex-column gap-3 p-3 " data-idtransfer="0">
                            
                            <div class="box-header d-flex justify-content-between">
                                <h3>{{ lang('Move Items', 'upload zone') }}</h3>
                                <div class="btns d-flex gap-1 align-items-center">
                                    {{-- <button class="btn btn-back-conf " >
                                        <img src="{{ asset('images/icons/dash/conf.svg') }}" alt="">
                                    </button> --}}
                                    <button class="btn btn-close">
                                        {{-- <img src="{{ asset('images/icons/dash/x.svg') }}" alt=""> --}}
                                    </button>
                                </div>
                            </div>
                            <div class="box-body d-flex flex-column gap-1">
                              <div class="row d-flex p-4">
                                <div class="col-lg-3">
                                    <ul class="list-unstyled workspace-listx">
                                      
                                        <li class="text-main bg-white p-2">
                                            <a href="?folder=0">
                                                <img
                                                src="{{ asset('images/icons/dash/starred.svg') }}"
                                                class="header-content-image"
                                                />    
                                                {{ lang('All Files', 'user') }}
                                                {{-- <span>156</span> --}}
                                            </a>
                                        </li>
                                        @foreach ($folders as $folder)
                                        <li class="text-main bg-white p-2">
                                            <a href="?folder={{$folder->id}}">
                                                <img
                                                src="{{ asset('images/icons/dash/starred.svg') }}"
                                                class="header-content-image"
                                                />    
                                                {{$folder->name}}
                                                {{-- <span>156</span> --}}
                                            </a>
                                        </li>
                                      
                                        @endforeach
                                    </ul>
                                    <button class="btn-create-folder-popup btn p-2 bg-dashboard text-white">
                                        {{ lang('Create new folder', 'upload zone') }}
                                    </button>
                                </div>
                                <div class="col-lg-9 h-100 xoxo">
                                    <table class="">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                {{-- <th class="text-center"></th> --}}
                                                <th class="text-center">{{ lang('Name', 'user') }}</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transfers as $transfer)
                                             <tr>
                                                <td>
                                                    <button class="btn-checky  b-color border-1  " data-idtransfer="{{$transfer->id}}"><i class="fa fa-check"></i></button>
                                                </td>
                                                {{-- <td>
                                                    <button class="btn-star @if ($transfer->starred) btn-star-starred @endif  border-0 p-2 bg-x text-light" data-idtransfer="{{$transfer->id}}"><i class="fa fa-star" aria-hidden="true"></i>
                                                    </button>
                                                </td> --}}
                                                <td style="max-width: 300px; overflow: hidden;">
                                                    {{$transfer->name}}
                                                </td>
                                                
                                             </tr>
                                            @endforeach
                                        </tbody>    
                                    </table>
                                    
                                </div>
                              </div>
                             </div>
                            <div class="box-footer d-flex justify-content-end gap-2">
                                    
                                    <button class="btn-cancel-move-file btn ">
                                        {{ lang('Cancel', 'upload zone') }}
                                    </button>
                                    <button class="btn-move-file btn p-2 bg-dashboard text-white">
                                        {{ lang('Move here', 'upload zone') }}
                                    </button>
                            </div>
                        </div>


                        <div class="newFolderPopup d-none p-3">
                           
                            <h2 class="fw-bold mb-2 ">{{ lang('Create a folder', 'upload zone') }}</h2>
                            <input type="text" class="p-2 mb-2 w-100 border-0 bg-light folder_name folderName" id="folder_name" placeholder="Enter your folder name" >
                            <div class="box-footer d-flex justify-content-end gap-2">
                                <button class="btn-create-folder btn p-2 bg-dashboard text-white d-flex align-items-center gap-1">
                                    {{ lang('Create', 'upload zone') }} 
                                    <img
                                    src="{{ asset('images/icons/plus.svg') }}"
                                    class="header-content-image"
                                    />
                                </button>
                                
                            </div>
                        
                        </div>
                        <div class="moveHerePopup d-none">
                            <ul class="p-2 list-unstyled ">
                                <li class="p-2 " data-idfile="0" > {{ lang('All Files', 'upload zone') }}</li>
                                @foreach ($folders as $folder)
                                <li class="p-2 "  data-idfile="{{$folder->id}}">
                                    {{$folder->name}}
                                </li>
                                @endforeach
                            </ul>
                            
                        </div>
                        <div class="messagePopup d-none ">
                                <div class="topHeader btn-info-hide text-main">
                                    <img
                            src="{{ asset('images/icons/dash/x.svg') }}"
                            class="header-content-image"
                            />
                                </div>
                                <div class="messageHeader">
                                    <i class="fa fa-info-circle fa-2x"></i>
                                </div>
                                <div class="messagebody">
                                    {{-- <span></span> --}}
                                </div>
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
