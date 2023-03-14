@extends('layouts.pages')
@section('title', $seo->seo_title)
@section('content')
<div class="imgbob-drag-zone" id="imgbob-drag-zone">
   <div class="ibobdrag imgbob-drag-zone-place">
      <div class="imgbob-home-modal">
         <div class="modal modal-blur fade" id="modal-full-width" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title">
                        <span>{{__('JPEG JPG PNG GIF')}}</span>
                        <span> - </span>
                        <span>{{__('Max '.$settings->max_filesize.' MB')}}</span>
                     </h5>
                     <span class="float-right imgbob-reset-button d-none">
                        <div class="upload-more" id="imgbob-upload-clickable">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                              <polyline points="9 15 12 12 15 15" />
                              <line x1="12" y1="12" x2="12" y2="21" />
                           </svg>
                           {{__('Upload more')}}
                        </div>
                     </span>
                     <button type="button" class="btn-close btn-close-here" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div class="imgbob-main-upload-section">
                        <div id="upload-process">
                           <div id="imgbob-uploader-box" class="imgbob-uploader-box">
                              <div id="imgbob-upload-clickable" class="imgbob-uploder-place">
                                 <div class="imgbob-upload-icon">
                                    <img class="img-responsivee" src="{{ asset('images/sections/upload.svg') }}" alt="">
                                 </div>
                                 <h3>{{__('Drag and drop or ')}}<span>{{__('browse')}}</span>{{__(' images here to upload')}}</h3>
                                 <p>{{__('You can upload '.$settings->onetime_uploads.' images in one time.')}}</p>
                                 <p><span>{{__('Max Filesize. '.$settings->max_filesize.' MB(s)')}}</span></p>
                              </div>
                           </div>
                           <div class="uploaded-success row" id="imgbob-preview-uploads"></div>
                           <div>
                              <div id="imgbob-drop-template" class="col-lg-4 mb-3 m-auto imgbob-uploader-area d-none">
                                 <div class="imgbob-card fade-in">
                                    <span class="success-icon-box d-none fade-in">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                    </span>
                                    <span class="error-icon-box d-none fade-in">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" /></svg>
                                    </span>  
                                    <div class="remove-icon">
                                       <i data-dz-remove class="fa fa-remove"></i>
                                   </div>                                    
                                    <img data-dz-thumbnail class="imgbob-upload-icon">
                                    <div class="imgbob-images-upload">
                                       <div class="imgbob-images-name" data-dz-name></div>
                                       <div class="imgbob-uploder-progress">
                                          <div class="alert alert-danger alert-error d-none" role="alert"></div>
                                          <textarea readonly class="form-control success-input d-none" rows="2"></textarea>
                                          <a href="#" class="btn btn-primary btn-view success-button d-none">{{__('View image')}}</a>
                                          <div class="progress upload-progress">
                                             <div data-dz-uploadprogress class="progress-bar bg-primary progress-bar-striped" role="progressbar" style="width: 0%"
                                                aria-valuemin="0" aria-valuemax="100">{{__('Uploading...')}}</div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="faa-bounce animated icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" /><polyline points="9 15 12 12 15 15" /><line x1="12" y1="12" x2="12" y2="21" /></svg></span>
                        <h2 class="imgbob-big-title">{{ $settings->home_heading }}</h2>
                        <p class="imgbob-description d-mobile-none">
                           {{ $settings->home_descritption }}
                        </p>
                        <button id="imgbob-upload-clickable" class="imgbob-strat-uploading btn btn-primary">{{__('Start uploading')}}</button>
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