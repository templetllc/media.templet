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
        @if(\Request::segment(1) != 'addition')
            <a href="{{ route('home') }}" class="dropdown-item">{{__('Home')}}</a>
            @if(userHasRole(Auth()->user()->permission, array(ADMIN_ROLE, CONTRIBUTOR_ROLE, MANAGER_ROLE)))
                <a href="{{ route('user.dashboard') }}" class="dropdown-item">{{__('Dashboard')}}</a>
            @endif
            @if(userHasRole(Auth()->user()->permission, array(ADMIN_ROLE, CONTRIBUTOR_ROLE, MANAGER_ROLE)))
                <a href="{{ route('user.gallery') }}" class="dropdown-item">{{__('My Gallery')}}</a>
            @endif
            <a href="{{ route('user.settings') }}" class="dropdown-item">{{__('Settings')}}</a>
            @if(userHasRole(Auth()->user()->permission, array(ADMIN_ROLE)))
                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">{{__('Admin')}}</a>
            @endif
            @if(userHasRole(Auth()->user()->permission, array(ADMIN_ROLE, APPROVER_ROLE)))
                <a href="{{ route('redirect.approvals', 'image') }}" class="dropdown-item">{{__('Approvals')}}</a>
            @endif
            <div class="dropdown-divider"></div>
        @endif
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
        <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
        @csrf
        </form>
    </div>
</div>
