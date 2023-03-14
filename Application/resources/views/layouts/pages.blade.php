<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      @include('includes.head')
   </head>
   <script>
      "use strict";
      const BASE_URL = "{{ url('/') }}";
   </script>
   @if($ads->mobile_ads != null)
   {!! $ads->mobile_ads !!}
   @endif
   <body @if(Request::path()== "/home" || Request::path()== "upload") class="imgbob_home_body" @endif @if(\Request::segment(1) == 'ib' || \Request::segment(1) == 'modal') class="bg-white" @endif>
   @if(Request::path()== "/home" || Request::path()== "upload")
   <script>
      "use strict";
        const SITE_URL              = "{{ url('/') }}";
        const MAX_FILES             = {{ $settings->onetime_uploads }};
        const MAX_SIZE              = {{ $settings->max_filesize }};
        const BIG_FILES_DETECTED    = "This File Type not Allowed."; 
   </script>
   @endif
   @if(Request::path() == "upload/modal" || str_contains(Request::path(), 'upload/modal') )
   <script>
      "use strict";
        const SITE_URL              = "{{ url('/') }}";
        const MAX_FILES             = 1;
        const MAX_SIZE              = {{ $settings->max_filesize }};
        const BIG_FILES_DETECTED    = "This File Type not Allowed."; 
   </script>
   @endif
   @if($__env->yieldContent('title') != "Page not found" && !str_contains(Request::path(), 'modal'))
   <header class="navbar navbar-expand-md navbar-light d-print-none @if(\Request::segment(1) == 'ib') sticky-top @endif">
      <div class="container-fluid">
         @if(\Request::segment(1) != 'addition') 
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
            </button>
         @endif
         <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal @if(\Request::segment(1) != 'addition') me-md-3 @endif">
            @if(\Request::segment(1) == 'preview') 
               <a href="{{ route('index', $client) }}">
            @else
               <a href="{{ route('home') }}">
            @endif
               <img src="{{ asset('images/main/'.$settings->logo) }}" width="110" height="32" alt="{{ $settings->site_name }}" class="navbar-brand-image">
            </a> @if(\Request::segment(1) == 'addition') <span class="imgbob__setup text-muted"> {{__('| SetUp')}}</span> @endif
         </h1>
         <div class="navbar-nav flex-row order-md-last">
            @guest
               @if(\Request::segment(1) != 'register' && \Request::segment(1) != 'login') 
               <li class="nav-item pe-0 pe-md-2 d-mobile-none">
                  <a href="{{ url('/login') }}" class="btn">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                        <path d="M20 12h-13l3 -3m0 6l-3 -3" />
                     </svg>
                     {{__('Sign in')}}
                  </a>
               </li>
               @endif
               @if(\Request::segment(1) != 'preview')
                  @if (Route::has('register'))
                     <li class="nav-item d-mobile-none">
                        <a href="{{ url('/register') }}" class="btn btn-primary">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <circle cx="9" cy="7" r="4" />
                              <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                              <path d="M16 11h6m-3 -3v6" />
                           </svg>
                           {{__('Create account')}}
                        </a>
                     </li>
                  @endif
                  <li class="nav-item pe-1 d-md-none mobile-icon">
                     <a href="{{ url('/login') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                           <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                           <path d="M20 12h-13l3 -3m0 6l-3 -3" />
                        </svg>
                     </a>
                  </li>
               @endif
            @else
               @if(Request::path()== '/upload')
                  <li class="nav-item me-2">
                     <a class="nav-link" href="#modal-full-width" data-bs-toggle="modal" data-bs-target="#modal-full-width">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                              <polyline points="9 15 12 12 15 15" />
                              <line x1="12" y1="12" x2="12" y2="21" />
                           </svg>
                        </span>
                        <span class="nav-link-title d-lg-none d-xl-inline-block">{{__('Upload')}}</span>
                     </a>
                  </li>
               @else
                  @if(\Request::segment(1) != 'upload' && \Request::segment(1) != 'preview')
                     <li class="nav-item me-2">
                        <a class="nav-link" href="{{ url('/upload') }}">
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                                 <polyline points="9 15 12 12 15 15" />
                                 <line x1="12" y1="12" x2="12" y2="21" />
                              </svg>
                           </span>
                           <span class="nav-link-title">{{__('Upload')}}</span>
                        </a>
                     </li>
                  @endif
               @endif
               @if(\Request::segment(1) != 'addition' && \Request::segment(1) != 'modal' && \Request::segment(1) != 'upload') 
                  <li class="nav-item pe-0 pe-md-3 d-mobile-none">
                     <a href="{{ url('user/gallery') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                           <line x1="15" y1="8" x2="15.01" y2="8" />
                           <rect x="4" y="4" width="16" height="16" rx="3" />
                           <path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" />
                           <path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                        </svg>
                        {{__('My Gallery')}}
                     </a>
                  </li>
               @endif
               <div class="nav-item dropdown">
                  <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                     <span class="avatar avatar-sm" style="background-image: url({{ asset('path/cdn/avatars/'.Auth::user()->avatar) }})"></span>
                     <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="mt-1 small text-muted">
                           @if(Auth::user()->permission == 2) {{__('User')}} @elseif(Auth::user()->permission == 1) {{__('Admin')}} @endif
                        </div>
                     </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                     @if(\Request::segment(1) != 'addition')
                     <a href="{{ url('user/dashboard') }}" class="dropdown-item">{{__('Dashboard')}}</a>
                     <a href="{{ url('user/gallery') }}" class="dropdown-item">{{__('My Gallery')}}</a>
                     <a href="{{ url('user/settings') }}" class="dropdown-item">{{__('Settings')}}</a>
                     <div class="dropdown-divider"></div>
                     @endif
                     <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
                     <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                        @csrf
                     </form>
                  </div>
               </div>
            @endguest
         </div>
         @if(\Request::segment(1) != 'addition') 
            <div class="collapse navbar-collapse order-md-first" id="navbar-menu">
               <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                  <ul class="navbar-nav">
                     {{-- <li class="nav-item @if(Request::path()== '/home') active @endif">
                        @if(\Request::segment(1) == 'preview') 
                           <a class="nav-link" href="{{ route('index', $client) }}">
                        @else
                           <a class="nav-link" href="{{ route('home') }}">
                        @endif
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <polyline points="5 12 3 12 12 3 21 12 19 12" />
                                 <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                 <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                              </svg>
                           </span>
                           <span class="nav-link-title">{{__('Back')}}</span>
                        </a>
                     </li> --}}
                     <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="history.back();">
                           <svg xmlns="http://www.w3.org/2000/svg" width="14.872" height="12.524" viewBox="0 0 14.872 12.524">
                             <path id="next" d="M18.643,9.709,13.164,4.229a.783.783,0,1,0-1.107,1.107L16.2,9.479H4.783a.783.783,0,1,0,0,1.566H16.2l-4.143,4.143a.783.783,0,1,0,1.107,1.107l5.479-5.479a.783.783,0,0,0,0-1.107Z" transform="translate(18.872 16.524) rotate(180)" fill="#232e3c"/>
                           </svg>
                           <span class="nav-link-title ms-2">{{__('Back')}}</span>
                        </a>
                     </li>
                     <li class="nav-item dropdown @if(\Request::segment(1) == 'page') active @endif d-none">
                        <a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <circle cx="12" cy="12" r="9" />
                                 <line x1="12" y1="8" x2="12.01" y2="8" />
                                 <polyline points="11 12 12 12 12 16 13 16" />
                              </svg>
                           </span>
                           <span class="nav-link-title d-lg-none d-xl-inline-block">{{__('About')}}</span>
                        </a>
                        <div class="dropdown-menu">
                           @if($composerPages->count() > 0)
                           @foreach ($composerPages as $composerPage)
                           <a class="dropdown-item  @if(\Request::segment(2) == $composerPage->slug) active @endif" href="{{ url('page/'.$composerPage->slug) }}">{{ $composerPage->title }}</a>
                           @endforeach
                           @else 
                           <div class="text-center">{{__('No Pages')}}</div>
                           @endif
                        </div>
                     </li>
                  </ul>
               </div>
            </div>
         @endif
      </div>
   </header>
   @endif
   @yield('content')
   @include('includes.footer')
   </body>
</html>