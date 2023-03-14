<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('includes.head')
    </head>
    <script>
        "use strict";
          const BASE_URL              = "{{ url('/') }}";
    </script>
    @if($ads->mobile_ads != null)
    {!! $ads->mobile_ads !!}
    @endif
    <body> 
        <div class="page">
            <header class="navbar navbar-expand-md navbar-light d-print-none">
                <div class="container-fluid">
                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-expanded="false">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                        <a href="{{ route('home') }}">
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
                                {{-- <a href="{{ url('user/dashboard') }}" class="dropdown-item">{{__('Dashboard')}}</a> --}}
                                <a href="{{ url('user/gallery') }}" class="dropdown-item">{{__('My Gallery')}}</a>
                                <a href="{{ url('user/settings') }}" class="dropdown-item">{{__('Settings')}}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="navbar-expand-md">
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="navbar navbar-light">
                        <div class="container-fluid">
                            <ul class="navbar-nav">
                                <li class="nav-item @if(Request::path()== 'home') active @endif">
                                    <a class="nav-link" href="{{ route('home') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                              <polyline points="5 12 3 12 12 3 21 12 19 12" />
                                              <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                              <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                           </svg>
                                        </span>
                                        <span class="nav-link-title">{{__('Home')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item @if(Request::path()== "user/gallery") active @endif">
                                    <a class="nav-link" href="{{ url('user/gallery') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <line x1="15" y1="8" x2="15.01" y2="8" />
                                                <rect x="4" y="4" width="16" height="16" rx="3" />
                                                <path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" />
                                                <path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">
                                            {{__('My Gallery')}}
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item @if(Request::path()== "user/settings") active @endif">
                                    <a class="nav-link" href="{{ url('user/settings') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"
                                                />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </span>
                                        <span class="nav-link-title">
                                            {{__('Settings')}}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                                <a href="{{ url('/upload') }}" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                                        <polyline points="9 15 12 12 15 15" />
                                        <line x1="12" y1="12" x2="12" y2="21" />
                                    </svg>
                                    {{__('Upload Images')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(Request::path() == 'home')
            <div class="account_pages pt-2 pb-4">
            @else
            <div class="account_pages py-4">
            @endif
                <div class="container-fluid">
                    @if(Request::path() != 'home')
                        <h2>@yield('title')</h2>
                        <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                            {{-- <li class="breadcrumb-item"><a href="{{ url('/user/dashboard') }}">{{__('User')}}</a></li> --}}
                            @if(!empty(Request::segment(2)))
                                <li class="breadcrumb-item"><span>{{ ucfirst(\Request::segment(2)) }}</span></li>
                            @endif
                        </ol>
                        @if($ads->user_account_ads != null)
                            <div class="topAds container mt-3 mb-3">
                                <div class="imgbob__top_ads text-center">
                                    {!! $ads->user_account_ads !!}
                                </div>
                            </div>
                        @endif
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>
        @include('includes.footer')
    </body>
</html>
