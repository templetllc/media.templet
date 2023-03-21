@if(Request::path() != "/")
   @if($__env->yieldContent('title') != "Page not found")
      <footer class="footer footer-transparent d-print-none">
         <div class="@if(\Request::segment(1) == "ib") container-fluid @else container @endif">
            <div class="text-center">
               <ul class="list-inline list-inline-dots mb-0 ms-0">
                  <li class="list-inline-item">
                     {{__('Copyright ©')}} <script>document.write(new Date().getFullYear())</script>
                     <a href="{{ url('/') }}" class="link-secondary">{{ $settings->site_name }}</a>.
                     {{__('All rights reserved.')}}
                  </li>
               </ul>
            </div>
         </div>
      </footer>
   @endif
@endif

<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert/sweetalert.min.js') }}"></script>
@if(Request::path()== "user/dashboard")
   @if($images->count() > 0)
      <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
      <script src="{{ asset('assets/libs/jqvmap/dist/jquery.vmap.min.js')}}"></script>
      <script src="{{ asset('assets/js/user/charts.js')}}"></script>
   @endif
@endif
<script src="{{ asset('assets/js/app.js') }}"></script>
@if(Request::path()== "upload" || Request::path()== "upload/modal" || str_contains(Request::path(), 'upload/modal') )
   <script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
   <script src="{{ asset('assets/js/home/ibob.js') }}"></script>
@endif
@if(\Request::segment(1) == "user" or \Request::segment(1) == "admin")
   <script src="{{ asset('assets/js/progressbar/progressbar.js') }}"></script>
@endif
@if(\Request::segment(1) == "home" || \Request::segment(1) == "public" || \Request::segment(2) == "gallery")

   <script src="{{ asset('assets/js/vendor/select2/select2.full.min.js') }}"></script>
@endif
@if(\Request::segment(1) == "user")
   <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.5.0/dist/lazyload.min.js"></script>
   <script src="{{ asset('assets/js/user/main.js') }}"></script>
   {{-- <script src="{{ asset('assets/js/filter.image.js') }}"></script> --}}
   <script type="text/javascript">
      var lazyLoadInstance = new LazyLoad({
         // Your custom settings go here
      });
   </script>
@endif
@if(\Request::segment(1) == "ib" || \Request::segment(1) == "modal" || \Request::segment(1) == "preview")
   <script src="{{ asset('assets/js/crop/croppie.min.js') }}"></script>
   <script src="{{ asset('assets/js/crop/crop.js') }}"></script>
   <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
   <script src="{{ asset('assets/js/tagin.min.js') }}"></script>
   <script src="{{ asset('assets/js/tagin.js') }}"></script>
   <script src="{{ asset('assets/js/home/main.js') }}"></script>
   <script src="{{ asset('assets/js/ib.js') }}"></script>
@endif
@if(empty(\Request::segment(1)) || Request::segment(2) == "gallery" || Request::segment(1) == "home" || Request::segment(1) == "public")
   <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.5.0/dist/lazyload.min.js"></script>
   <script src="{{ asset('assets/js/home/main.js') }}"></script>
   <script src="{{ asset('assets/js/filter.image.js') }}"></script>
   <script type="text/javascript">
      var lazyLoadInstance = new LazyLoad({
         // Your custom settings go here
      });
   </script>
@endif
@if(Request::segment(2) == "approvals")
   <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@17.5.0/dist/lazyload.min.js"></script>
   <script src="{{ asset('assets/js/approval.image.js') }}"></script>
   <script type="text/javascript">
      var lazyLoadInstance = new LazyLoad({
         // Your custom settings go here
      });
   </script>
@endif
@if(Request::segment(2) == "approvals" && Request::segment(3) == "detail")
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
   <script src="{{ asset('assets/feedback/js/feedback.js') }}" type="text/javascript"></script>
@endif
@yield('js_after')
{!! NoCaptcha::renderJs() !!}
@if($settings->site_analytics != null)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings->site_analytics }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ $settings->site_analytics }}');
   </script>
@endif

