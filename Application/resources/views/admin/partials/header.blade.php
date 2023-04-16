<header class="navbar navbar-expand-md navbar-light d-none d-lg-flex d-print-none">
   <div class="container-xl">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-nav flex-row order-md-last">
         <div class="nav-item d-none d-md-flex me-3">
            <a href="{{ url('admin/messages') }}" class="nav-link px-0">
               <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10" />
                  <path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2" />
               </svg>
               @if($messages > 0 )
               <span class="badge bg-red faa-flash animated"></span>
               @endif
            </a>
         </div>
         @include('includes/user-dropdown')
      </div>
      <div class="collapse navbar-collapse" id="navbar-menu">
         <div class="ms-md-auto py-2 py-md-0 me-md-4 order-first order-md-last flex-grow-1">
            <form action="{{ route('uploads') }}" method="GET">
               <div class="input-icon">
                  <span class="input-icon-addon">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="10" cy="10" r="7"></circle>
                        <line x1="21" y1="21" x2="15" y2="15"></line>
                     </svg>
                  </span>
                  <input type="text" name="q" class="form-control" placeholder="Search on uploads...">
               </div>
            </form>
         </div>
      </div>
   </div>
</header>
