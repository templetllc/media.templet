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
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/main/'.$settings->logo) }}" width="110" height="32" alt="{{ $settings->site_name }}" class="navbar-brand-image" />
                        </a>
                    </h1>
                    <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item dropdown">
    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
        <span class="avatar avatar-sm" style="background-image: url({{ asset('path/cdn/avatars/'.Auth::user()->avatar) }})"></span>
        <div class="d-none d-xl-block ps-2">
            <div>{{ Auth::user()->name }}</div>
            <div class="mt-1 small text-muted">
                {{getUserRole(Auth::user())}}
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
            <div class="account_pages py-4">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
        @include('includes.footer')
    </body>
</html>
