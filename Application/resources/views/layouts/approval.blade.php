<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('includes.head')
    </head>
    <script>
        "use strict";
          const BASE_URL              = "{{ url('/') }}";
    </script>

    <body>
        <div class="page">
            <header class="navbar navbar-expand-md navbar-light d-print-none">
                <div class="container-fluid">
                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    @if(Request::segment(3)=="detail")
                    <div class="navbar-nav">
                        <a class="nav-link d-flex p-0" href="{{ route('approvals', array_merge(array($type, $status), request()->query())) }}">
                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 20 20" fill="none">
                                <path
                                    stroke="#3B3A3C"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M18 10H2m0 0l7-7m-7 7l7 7"
                                />
                            </svg>
                            <span class="nav-link-title color-black-olive">
                                Back
                            </span>
                        </a>
                    </div>
                    @endif
                    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0">
                        <a href="{{ route('approvals', $type) }}">
                            <img src="{{ asset('images/main/'.$settings->logo) }}" width="110" height="32" alt="{{ $settings->site_name }}" class="navbar-brand-image" />
                        </a>
                    </h1>
                    <div class="navbar-nav flex-row order-md-last">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                                <span class="avatar avatar-sm rounded-circle avatar-image" style="background-image: url({{ asset('path/cdn/avatars/'.Auth::user()->avatar) }})"></span>
                                <div class="d-none d-xl-block ps-2">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="mt-1 small text-muted">
                                        @if(Auth::user()->permission == 2) {{__('User')}} @elseif(Auth::user()->permission == 1) {{__('Admin')}} @endif
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            @if((Request::segment(2)=="approvals") && (Request::segment(3)!="detail"))
                <div class="navbar-expand-md">
                    <div class="collapse navbar-collapse" id="navbar-menu">
                        <div class="navbar navbar-light">
                            <div class="container-fluid">
                                <ul class="navbar-nav">
                                    <li class="nav-item active">
                                        <a class="nav-link pl-0" href="{{ route('approvals', array('images', $status)) }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block w-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13.031" height="13.031" viewBox="0 0 13.031 13.031">
                                                    <path
                                                        id="image-"
                                                        d="M13.076,2H3.955A1.955,1.955,0,0,0,2,3.955v9.122a1.955,1.955,0,0,0,1.955,1.955h9.122A1.83,1.83,0,0,0,13.4,15l.2-.046h.078l.241-.091L14,14.816c.065-.039.137-.072.2-.117a2.462,2.462,0,0,0,.248-.208l.046-.059a1.75,1.75,0,0,0,.176-.208l.059-.085a1.5,1.5,0,0,0,.117-.228.653.653,0,0,0,.046-.1c.033-.078.052-.163.078-.248v-.1a1.693,1.693,0,0,0,.065-.391V3.955A1.955,1.955,0,0,0,13.076,2ZM3.955,13.728a.652.652,0,0,1-.652-.652V10.268l2.144-2.15a.652.652,0,0,1,.925,0l5.6,5.61Zm9.773-.652a.529.529,0,0,1-.1.326.618.618,0,0,1-.059.078L10.086,9.995l.573-.573a.652.652,0,0,1,.925,0l2.144,2.15Zm0-3.349L12.5,8.516a2.007,2.007,0,0,0-2.763,0l-.573.573L7.291,7.212a2.007,2.007,0,0,0-2.763,0L3.3,8.424V3.955A.652.652,0,0,1,3.955,3.3h9.122a.652.652,0,0,1,.652.652ZM9.493,4.606a.977.977,0,1,0,.691.286A.977.977,0,0,0,9.493,4.606Z"
                                                        transform="translate(-2 -2)"
                                                        fill="{{$type == 'images' ? '#23DD8B' : '#232e3c'}}"
                                                    />
                                                </svg>
                                            </span>
                                            <span class="nav-link-title {{$type == 'images' ? 'color-mountain-meadow NexaBold' : ''}}">
                                                {{__('Images')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{ route('approvals', array('icons', $status)) }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block w-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" id="ico-icons" width="13" height="13" viewBox="0 0 13 13">
                                                    <path
                                                        id="Path_2454"
                                                        data-name="Path 2454"
                                                        d="M4.25,9.911A5.661,5.661,0,0,1,9.911,4.25h1.677A5.661,5.661,0,0,1,17.25,9.911v1.677a5.661,5.661,0,0,1-5.661,5.661H9.911A5.661,5.661,0,0,1,4.25,11.589Zm5.661-4.4a4.4,4.4,0,0,0-4.4,4.4v1.677a4.4,4.4,0,0,0,4.4,4.4h1.677a4.4,4.4,0,0,0,4.4-4.4V9.911a4.4,4.4,0,0,0-4.4-4.4Z"
                                                        transform="translate(-4.25 -4.25)"
                                                        fill="{{$type == 'icons' ? '#23DD8B' : '#232e3c'}}"
                                                        fill-rule="evenodd"
                                                    />
                                                    <path
                                                        id="Path_2455"
                                                        data-name="Path 2455"
                                                        d="M8.75,10.5A1.75,1.75,0,0,1,10.5,8.75h3a1.75,1.75,0,0,1,1.75,1.75v3a1.75,1.75,0,0,1-1.75,1.75h-3A1.75,1.75,0,0,1,8.75,13.5Zm1.75-.25a.25.25,0,0,0-.25.25v3a.25.25,0,0,0,.25.25h3a.25.25,0,0,0,.25-.25v-3a.25.25,0,0,0-.25-.25Z"
                                                        transform="translate(-5.5 -5.5)"
                                                        fill="{{$type == 'icons' ? '#23DD8B' : '#232e3c'}}"
                                                        fill-rule="evenodd"
                                                    />
                                                </svg>
                                            </span>
                                            <span class="nav-link-title {{$type == 'icons' ? 'color-mountain-meadow NexaBold' : ''}}">{{__('Icons')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-none">
                                        <a class="nav-link" href="{{ url('/') }}">
                                            <span class="nav-link-icon d-md-none d-lg-inline-block w-3">
                                                <svg id="stop" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13">
                                                    <path id="Path_2454" data-name="Path 2454" d="M4.25,9.911A5.661,5.661,0,0,1,9.911,4.25h1.677A5.661,5.661,0,0,1,17.25,9.911v1.677a5.661,5.661,0,0,1-5.661,5.661H9.911A5.661,5.661,0,0,1,4.25,11.589Zm5.661-4.4a4.4,4.4,0,0,0-4.4,4.4v1.677a4.4,4.4,0,0,0,4.4,4.4h1.677a4.4,4.4,0,0,0,4.4-4.4V9.911a4.4,4.4,0,0,0-4.4-4.4Z" transform="translate(-4.25 -4.25)" fill="#232e3c" fill-rule="evenodd"/>
                                                    <path id="Path_2455" data-name="Path 2455" d="M8.75,10.5A1.75,1.75,0,0,1,10.5,8.75h3a1.75,1.75,0,0,1,1.75,1.75v3a1.75,1.75,0,0,1-1.75,1.75h-3A1.75,1.75,0,0,1,8.75,13.5Zm1.75-.25a.25.25,0,0,0-.25.25v3a.25.25,0,0,0,.25.25h3a.25.25,0,0,0,.25-.25v-3a.25.25,0,0,0-.25-.25Z" transform="translate(-5.5 -5.5)" fill="#232e3c" fill-rule="evenodd"/>
                                                </svg>
                                            </span>
                                            <span class="nav-link-title">
                                                {{__('Icons')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navbar-expand-md">
                    <div class="collapse navbar-collapse" id="navbar-menu">
                        <div class="navbar navbar-light">
                            <div class="container-fluid">
                                <ul class="navbar-nav" id="countImages">
                                    <!-- <li class="nav-item">
                                        <a class="nav-link pl-0" href="{{ route('approvals', $type) }}">
                                            <span class="nav-link-title {{$status == '' ? 'color-mountain-meadow NexaBold' : ''}}">No status ({{ isset($count_approval[""]) ? $count_approval[""]:0 }})</span>
                                        </a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link pl-0" href="{{ route('approvals', array($type)) }}">
                                            <span class="nav-link-title {{($status == 'all' || $status == '') ? 'color-mountain-meadow NexaBold' : ''}}">All ({{ $counts }})</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('approvals', array($type, 'approved')) }}" >
                                            <span class="nav-link-title {{$status == 'approved' ? 'color-mountain-meadow NexaBold' : ''}}">Approved ({{ isset($count_approval[1]) ? $count_approval[1]:0 }})</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('approvals', array($type, 'unapproved')) }}">
                                            <span class="nav-link-title {{$status == 'unapproved' ? 'color-mountain-meadow NexaBold' : ''}}">Unapproved ({{ isset($count_approval[0]) ? $count_approval[0]:0 }})</span>
                                        </a>
                                    </li>
                                </ul>
                                {{-- <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last"> --}}
                                <ul class="navbar-nav flex-row order-md-last pt-1" id="actionImages">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" onclick="event.preventDefault();">
                                            <span class="nav-link-title NexaBold color-black-olive">Select (<span id="selected">0</span>)</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span class="nav-link-title NexaBold color-black-olive" data-toggle="selectAll">Select all</span>
                                        </a>
                                    </li>
                                    <li class="nav-item me-2">
                                        <a class="nav-link" href="#">
                                            <span class="nav-link-title NexaBold color-black-olive" data-toggle="deselectAll">Deselect all</span>
                                        </a>
                                    </li>
                                    <li class="nav-item me-2">
                                        <a href="#" class="btn btn-outline-primary w-100" data-toggle="unapprovalImage">
                                            {{__('Unapproved')}}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="btn btn-primary w-100" data-toggle="approvalImage">
                                            {{__('Approved')}}
                                        </a>
                                    </li>
                                </ul>
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="account_pages">
                <div class="container-fluid bg-core-content-e">
                    @yield('content')
                </div>
            </div>
        </div>
        @include('includes.footer')
    </body>
</html>
