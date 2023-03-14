@extends('layouts.pages')
@section('title', $seo->seo_title)
@section('content')
<div class="imgbob-drag-zone" id="imgbob-drag-zone">
   <div class="ibobdrag imgbob-drag-zone-place">
      @if($ads->home_ads_top != null)
      <div class="topAds container">
         <div class="imgbob__top_ads text-center">
            {!! $ads->home_ads_top !!}
         </div>
      </div>
      @endif
      <div class="imgbob-uploader-out" id="imgbob-uploader-out">
         <div id="imgbob-upload-clickable" class="imgbob-home-page-content imgbob-uploder-out-place">
            <div class="container-xl">
               <div class="row justify-content-center">
                  <div class="col-10">
                     <div class="imgbob-home-text text-center">
                        <h2 class="imgbob-big-title">{{ $settings->home_heading }}</h2>
                        <p class="imgbob-description d-mobile-none">
                           {{ $settings->home_descritption }}
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      @if($ads->home_ads_bottom != null)
      <div class="bottom container">
         <div class="imgbob__bottom_ads text-center">
            {!! $ads->home_ads_bottom !!}
         </div>
      </div>
      @endif
   </div>
</div>
@endsection