<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
   <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
      <span class="navbar-toggler-icon"></span>
      </button>
      <h1 class="navbar-brand navbar-brand-autodark">
         <a href=".">
         <img src="{{ asset('images/main/'.$settings->logo) }}" width="110" height="32" alt="{{ $settings->site_name }}" class="navbar-brand-image">
         </a>
      </h1>
      <div class="navbar-nav flex-row d-lg-none">
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
               <a href="{{ url('admin/dashboard') }}" class="dropdown-item">{{__('Dashboard')}}</a>
               <a href="{{ url('admin/profile') }}" class="dropdown-item">{{__('Update profile')}}</a>
               <div class="dropdown-divider"></div>
               <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
               <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
               </form>
            </div>
         </div>
      </div>
      <div class="collapse navbar-collapse" id="navbar-menu">
         <ul class="navbar-nav pt-lg-3">
            <li class="nav-item @if(\Request::segment(2) == "dashboard") active @endif">
            <a class="nav-link" href="{{ url('admin/dashboard') }}" >
               <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <polyline points="5 12 3 12 12 3 21 12 19 12" />
                     <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                     <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                  </svg>
               </span>
               <span class="nav-link-title">
               {{__('Dashboard')}}
               </span>
            </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "users") active @endif">
            <a class="nav-link" href="{{ url('admin/users') }}" >
               <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <circle cx="9" cy="7" r="4" />
                     <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                     <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                     <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                  </svg>
               </span>
               <span class="nav-link-title">
               {{__('Manage users')}}
               </span>
            </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "uploads") active @endif">
               <a class="nav-link" href="{{ url('admin/uploads') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                        <polyline points="9 15 12 12 15 15" />
                        <line x1="12" y1="12" x2="12" y2="21" />
                     </svg>
                  </span>
                  <span class="nav-link-title">
                  {{__('Manage uploads')}}
                  </span>
               </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "presets") active @endif">
               <a class="nav-link" href="{{ route('preset.index') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                        <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                     </svg>
                  </span>
                  <span class="nav-link-title">
                  {{__('Manage presets')}}
                  </span>
               </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "ads") active @endif">
               <a class="nav-link" href="{{ route('category.index') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <polyline points="7 8 3 12 7 16" /><polyline points="17 8 21 12 17 16" />
                        <line x1="14" y1="4" x2="10" y2="20" />
                     </svg> 
                  </span>
                  <span class="nav-link-title">
                  {{__('Manage Categories')}}
                  </span>
               </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "ads") active @endif d-none">
               <a class="nav-link" href="{{ url('admin/ads') }}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <polyline points="7 8 3 12 7 16" /><polyline points="17 8 21 12 17 16" />
                        <line x1="14" y1="4" x2="10" y2="20" />
                     </svg> 
                  </span>
                  <span class="nav-link-title">
                  {{__('Manage Ads')}}
                  </span>
               </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "messages") active @endif d-none">
            <a class="nav-link" href="{{ url('admin/messages') }}" >
               <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" />
                     <path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" />
                  </svg>
               </span>
               <span class="nav-link-title">
               {{__('Inbox Messages')}}
               </span>
            </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "pages") active @endif d-none">
            <a class="nav-link" href="{{ url('admin/pages') }}" >
               <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                     <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                     <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                  </svg>
               </span>
               <span class="nav-link-title">
               {{__('Website pages')}}
               </span>
            </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "settings") active @endif">
            <a class="nav-link" href="{{ url('admin/settings') }}" >
               <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                     <circle cx="12" cy="12" r="3" />
                  </svg>
               </span>
               <span class="nav-link-title">
               {{__('Website settings')}}
               </span>
            </a>
            </li>
            <li class="nav-item @if(\Request::segment(2) == "profile") active @endif">
            <a class="nav-link" href="{{ url('admin/profile') }}" >
               <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                     <circle cx="12" cy="12" r="3" />
                  </svg>
               </span>
               <span class="nav-link-title">
               {{__('Admin profile')}}
               </span>
            </a>
            </li>
         </ul>
      </div>
   </div>
</aside>