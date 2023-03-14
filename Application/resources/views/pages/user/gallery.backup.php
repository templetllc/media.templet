@extends('layouts.user')
@section('title', 'My Gallery')
@section('content')
<div class="imgbob_dash mt-3">
   <div class="content" data-base="/user/gallery">

      @include('includes.filter')

      <div class="row row-cards images-container">
         @foreach ($images as $image)
         <div class="item-image col-sm-6 col-lg-4 image{{ $image->id }}" data-category="{{ empty($image->category) ? 'uncategorized': str_replace(" ","_",strtolower($image->category)) }}" data-preset="{{ empty($image->preset) ? 'original_size': $image->preset }}" data-tags="{{ $image->tags }}" data-date="{{ $image->created_at->format('Y').'_'.$image->created_at->format('n') }}">
            <div class="card card-sm">
               <a href="{{ url('ib/'.$image->image_id) }}" class="d-block">
                  <img src="{{ $image->image_path }}" class="gallery-image card-img-top">
               </a>
               <div class="card-body">
                  <div class="d-flex align-items-center">
                     <span class="avatar me-3 rounded" style="background-image: url({{ asset('path/cdn/avatars/'.$image->user->avatar) }})"></span>
                     <div>
                        <!-- <div>{{ $image->user->name }}</div> -->
                        <!-- <small class="text-muted">{{ Carbon\Carbon::parse($image->created_at)->diffForHumans() }}</small> -->

                        <h3 class="mb-0">{{ empty($image->description) ? 'Original Size' : $image->description }}</h3>
                        <p class="mb-0">{{ $image->width.'x'.$image->height }}</p>  
                     </div>
                     <div class="ms-auto">
                        <!-- <span class="text-muted">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <circle cx="12" cy="12" r="2" />
                              <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                           </svg>
                           {{ $image->views }}
                        </span> -->

                        <input class="position-relative" type="text" id="{{ $image->id }}" value="{{ $image->image_path }}" readonly="readonly" style="z-index: -1;">
                        <a href="#" data-id="{{ $image->id }}" class="text-muted ico-copy ms-2 border-0 bg-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                               <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                               <rect x="8" y="8" width="12" height="12" rx="2" />
                               <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                            </svg>
                        </a>

                        <a href="{{ route('download.image', $image->image_id) }}" class="text-muted ms-2 border-0 bg-transparent">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" />
                              <line x1="12" y1="13" x2="12" y2="22" />
                              <polyline points="9 19 12 22 15 19" />
                           </svg>
                        </a>

                        <a href="#" data-id="{{ $image->id }}" id="deleteImage" class="text-muted ms-2">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @endforeach
      </div>
      {{$images->links()}}
      <div class="empty empty-gallery @if($images->count() != 0) d-none  @endif">
         <div class="empty-img"><img src="{{ asset('images/sections/empty.svg') }}" height="128" alt="">
         </div>
         <p class="empty-title">{{__('No images found')}}</p>
         <p class="empty-subtitle text-muted">
            {{__('Upload the images, and they will all appear here.')}}
         </p>
         <div class="empty-action">
            <a href="{{ url('/upload') }}" class="btn">
               <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"></path>
                  <polyline points="9 15 12 12 15 15"></polyline>
                  <line x1="12" y1="12" x2="12" y2="21"></line>
              </svg>
              {{__('Start Uploading')}}
            </a>
          </div>
       </div>
   </div>
</div>
@endsection