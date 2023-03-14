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
                    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                        <a href="{{ route('index', $client) }}">
                            <img src="{{ asset('images/main/'.$settings->logo) }}" width="110" height="32" alt="{{ $settings->site_name }}" class="navbar-brand-image" />
                        </a>
                    </h1>
                    <div class="navbar-nav flex-row order-md-last">
                        <div class="nav-item dropdown">
                            <a href="{{ url('/login') }}" class="btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M20 12h-13l3 -3m0 6l-3 -3" />
                                </svg>
                                {{__('Sign in')}}
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <div class="account_pages py-4">
                <div class="container-fluid">
                    @if(\Request::segment(1) == "public")
                        <h2>@yield('title')</h2>
                        <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                            <li class="breadcrumb-item"><a href="{{ route('index', $client) }}">{{__('Home')}}</a></li>
                            {{-- <li class="breadcrumb-item"><a href="{{ url('/user/dashboard') }}">{{__('User')}}</a></li> --}}
                            @if(!empty(Request::segment(2)))
                                <li class="breadcrumb-item"><span>{{ ucfirst(\Request::segment(2)) }}</span></li>
                            @endif
                        </ol>
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>
        @include('includes.footer')
    </body>
</html>
