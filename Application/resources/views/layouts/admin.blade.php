<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      @include('admin.includes.head')
   </head>
   <script>
      "use strict";
        const BASE_URL              = "{{ url('/') }}";
   </script>
   <body class="antialiased">
      <div class="page">
         @include('admin.partials.nav')

         @include('admin.partials.header')
              
         <div class="content">
            <div class="container-xl">
               <h2>@yield('title')</h2>
               <ol class="breadcrumb breadcrumb-alternate mb-3" aria-label="breadcrumbs">
                  <?php $segments = ''; ?>
                  @foreach(Request::segments() as $segment)
                  <?php $segments .= '/'.$segment; ?>
                  <li class="breadcrumb-item">
                     <a href="{{ url($segments) }}">{{$segment}}</a>
                  </li>
                  @endforeach
               </ol>
               @yield('content')
            </div>
         </div>
      </div>
      @include('admin.includes.footer')
   </body>
</html>