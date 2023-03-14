@extends('layouts.pages')
@section('title', 'Image - '.$image->image_id)

@section('content')
<div class="view_image_page pt-3">
    <div class="container-fluid">
      <div class="row">
            <div class="col-lg-12 m-auto text-center">
                @if($image->user_id == Auth()->user()->id || Auth()->user()->permission == 1)
                    <div class="row">
                        <div class="col-lg-9 m-auto">
                            @if(Session::has('alert-saved')) 
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @empty($image->category)
                                        Saved successfully.
                                    @else
                                        The image was successfully saved. If you want to go to the main gallery, <a href="#" class="alert-link" data-category='{{ $image->category }}'>here.</a>
                                    @endempty
                                </div>
                            @endif
                            @if(substr(strrchr($image->image_path, '.'), 1) == 'gif')
                                <img src="{{ $image->image_path }}" class="img-fluid image-container">
                            @else
                                <div class="image-container" data-toggle="crop" data-image="{{ $image->image_path }}" data-width="{{ $image->width }}" data-height="{{ $image->height }}">
                                    {{-- <img src="{{ $image->image_path }}" class="img-fluid image-container"> --}}
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-3 image-info">
                           <div class="card presets-box">
                              <div class="card-body">
                                 
                                 <h2>{{__('Image description')}}</h2>
                                 <p class="mb-0">{{ empty($image->description) ? 'Original Size' : $image->description }}</p>
                                 <p class="mb-0">{{ $image->width.'x'.$image->height }}</p>
                                 <p class="mb-0">{{ formatBytes($image->image_size) }}</p>

                                 <br>

                                 <div>
                                    <div>
                                    {{__('Autor:')}} {{{ isset($image->user->name) ? $image->user->name : 'Anonymous' }}}
                                    </div>
                                    <small class="text-muted">{{ Carbon\Carbon::parse($image->created_at)->diffForHumans() }}</small>
                                 </div>

                                 <br>

                                 <div class="">
                                    <span class="text-muted">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                          <circle cx="12" cy="12" r="2" />
                                          <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                       </svg>
                                       {{ $image->views }}
                                    </span>
                                    @isset($image->user->id)
                                       @if(Auth::user()->id == $image->user->id)
                                          <a href="#" data-id="{{ $image->id }}" id="deleteImage" class="text-muted ms-2">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                          </a>
                                       @endif
                                    @endisset
                                 </div>

                                 <hr class="my-3">

                                 <div class="form-group">
                                    <label for="category">{{__('Category')}}</label>
                                    {{-- <input type="text" id="category" class="form-control fm40" placeholder="Category " name="category" required> --}}
                                    <select id="category" name="category" class="form-control fm40 select-category" required>
                                       @if(count($categories) > 0)
                                          <option value="">{{__('- Select Category -')}}</option>
                                          @foreach($categories as $category)
                                             <option value="{{ $category->category }}" @if($category->category == $image->category) selected="select" @endif >{{ $category->category }}</option>
                                          @endforeach
                                       @else
                                          <option value="">{{__('No Category')}}</option>
                                       @endif
                                    </select>
                                 </div>
                                 <div class="form-group">
                                    <label for="tags">{{__('Tags')}}</label>
                                    <input type="text" id="tags" class="form-control fm40 tagin" data-toggle="tagin" data-placeholder="" name="tags" data-transform="input => input.toUpperCase()" value="{{ $image->tags }}" onkeypress="return check(event)">
                                 </div>

                                @if(substr(strrchr($image->image_path, '.'), 1) != 'gif')
                                 <div class="form-group presets">
                                    <label for="preset">{{__('Preset')}}</label>
                                    <select name="preset" class="form-control fm40 select-preset" data-toggle="preset">
                                       @if(count($presets) > 0)
                                          <option value="">{{__('- Select Preset -')}}</option>
                                          @foreach($presets as $preset)
                                             <option value="{{ $preset->id }}" data-value="{{ $preset->value }}">{{ $preset->preset." (".$preset->value.")" }}</option>
                                          @endforeach
                                       @else
                                          <option value="300x300">{{__('Default (300x300)')}}</option>
                                       @endif
                                    </select>
                                 </div>
                                @endif

                                <div class="input-group form-group">
                                    <label class="form-check me-4">
                                       <input class="form-check-input" type="checkbox" name="thumbnail" id="thumbnail" {{ ($image->thumbnail == 1) ? 'checked' : ''}}>
                                       <span class="form-check-label">Icon</span>
                                    </label>

                                     <label class="form-check">
                                       <input class="form-check-input" type="checkbox" name="gallery" id="gallery" {{ ($image->gallery == 1) ? 'checked' : ''}}>
                                       <span class="form-check-label">Gallery</span>
                                    </label>
                                 </div>

                                 <hr class="my-3">

                                 <h2>{{__('Image link')}}</h2>
                                 <div class="input-group form-group">
                                    {{-- <input class="form-control sharelink" name="sharelink" id="sharelink" value="{{ url('ib/'.$image->image_id) }}" readonly> --}}
                                    <input class="form-control sharelink" name="sharelink" id="sharelink" value="{{ $image->image_path }}" readonly>
                                    <button id="copy" class="copy-btn btn" type="button">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                          <rect x="8" y="8" width="12" height="12" rx="2" />
                                          <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                       </svg>
                                       <span class="copy">{{__('Copy')}}</span>
                                       <span class="copied d-none">{{__('Copied')}}</span>
                                    </button>
                                 </div>

                                 <hr class="my-3 d-none">
                                 <div class="input-group form-group d-none">
                                    <label class="form-check">
                                       <input class="form-check-input" type="checkbox" name="duplicate" id="duplicate" disabled>
                                       <span class="form-check-label">Do you want to keep the original size?</span>
                                    </label>
                                 </div>

                                 <div class="actions">
                                    <button class="btn btn-primary" data-toggle="save" data-id="{{ $image->image_id }}">Save</button>

                                    <a href="{{ route('download.image', $image->image_id) }}" class="btn btn-primary ms-3 pull-right">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                          <path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" />
                                          <line x1="12" y1="13" x2="12" y2="22" />
                                          <polyline points="9 19 12 22 15 19" />
                                       </svg>
                                       {{__('Download')}}
                                    </a>
                                 </div>
                              </div>
                           </div>
                        
                        </div>
                    </div>
                @else
                     <div class="row">
                        <div class="col-lg-9 m-auto">
                            <div class="image-container">
                                 <img src="{{ $image->image_path }}" class="img-fluid image-container">
                            </div>
                        </div>
                        <div class="col-lg-3 image-info">
                           <div class="card presets-box">
                              <div class="card-body">
                                 
                                 <h2>{{__('Image description')}}</h2>
                                 <p class="mb-0">{{ empty($image->description) ? 'Original Size' : $image->description }}</p>
                                 <p class="mb-0">{{ $image->width.'x'.$image->height }}</p>
                                 <p class="mb-0">{{ formatBytes($image->image_size) }}</p>

                                 <br>

                                 <div>
                                    <div>
                                    {{__('Autor:')}} {{{ isset($image->user->name) ? $image->user->name : 'Anonymous' }}}
                                    </div>
                                    <small class="text-muted">{{ Carbon\Carbon::parse($image->created_at)->diffForHumans() }}</small>
                                 </div>

                                 <br>

                                 <div class="">
                                    <span class="text-muted">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                          <circle cx="12" cy="12" r="2" />
                                          <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                       </svg>
                                       {{ $image->views }}
                                    </span>
                                 </div>

                                 <hr class="my-3">

                                 <div class="form-group">
                                    <label for="category">{{__('Category')}}</label>
                                    <input type="text" id="category" class="form-control fm40" placeholder="Category " name="category"  value="{{ $image->category }}" readonly="readonly">
                                 </div>

                                 <div class="form-group">
                                    <label for="tags">{{__('Tags')}}</label>
                                    <input type="text" id="tags" class="form-control fm40" name="tags" value="{{ $image->tags }}" readonly="readonly">
                                 </div>

                                 <hr class="my-3">

                                 <h2>{{__('Image link')}}</h2>
                                 <div class="input-group form-group">
                                    {{-- <input class="form-control sharelink" name="sharelink" id="sharelink" value="{{ url('ib/'.$image->image_id) }}" readonly> --}}
                                    <input class="form-control sharelink" name="sharelink" id="sharelink" value="{{ $image->image_path }}" readonly>
                                    <button id="copy" class="copy-btn btn" type="button">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                          <rect x="8" y="8" width="12" height="12" rx="2" />
                                          <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                       </svg>
                                       <span class="copy">{{__('Copy')}}</span>
                                       <span class="copied d-none">{{__('Copied')}}</span>
                                    </button>
                                 </div>

                                 <hr class="my-3">
                                 <div class="actions">
                                    <a href="{{ route('download.image', $image->image_id) }}" class="btn btn-primary ms-3 pull-right">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                          <path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" />
                                          <line x1="12" y1="13" x2="12" y2="22" />
                                          <polyline points="9 19 12 22 15 19" />
                                       </svg>
                                       {{__('Download')}}
                                    </a>
                                 </div>
                              </div>
                           </div>
                        
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- <div class="view_image_footer mt-3">
        <div class="container-fluid">
         <div class="page-header py-3">
            <div class="row align-items-center">
               <div class="col-auto">
                  @if($image->user_id != null) 
                  <span class="avatar avatar-md" style="background-image: url({{ asset('path/cdn/avatars/'.$image->user->avatar) }})"></span>
                  @else 
                  <span class="avatar avatar-md" style="background-image: url({{ asset('path/cdn/avatars/default.png') }})"></span>
                  @endif
               </div>
               <div class="col">
                  @if($image->user_id != null) 
                  <h2 class="page-title">{{ $image->user->name }}</h2>
                  @else 
                  <h2 class="page-title">{{__('Anonymous')}}</h2>
                  @endif
                  <div class="page-subtitle">
                     <div class="row">
                        <div class="col-auto d-none d-md-flex">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <rect x="4" y="5" width="16" height="16" rx="2" />
                              <line x1="16" y1="3" x2="16" y2="7" />
                              <line x1="8" y1="3" x2="8" y2="7" />
                              <line x1="4" y1="11" x2="20" y2="11" />
                              <rect x="8" y="15" width="2" height="2" />
                           </svg>
                           <span class="text-reset">{{ date("d/m/y  H:i A", strtotime($image->created_at))}}</span>
                        </div>
                        <div class="col-auto">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <circle cx="12" cy="12" r="2" />
                              <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                           </svg>
                           <span class="text-reset">{{ $image->views }}</span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-auto d-none d-md-flex">
                  @if(Auth::user() && $image->user_id == Auth::user()->id)
                  <a href="{{ url('/user/gallery') }}" class="btn btn-primary">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="15" y1="8" x2="15.01" y2="8" />
                        <rect x="4" y="4" width="16" height="16" rx="3" />
                        <path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" />
                        <path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                     </svg>
                     {{__('My gallery')}}
                  </a>
                  @endif
                  <a href="{{ route('download.image', $image->image_id) }}" class="btn btn-primary ms-3">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" />
                        <line x1="12" y1="13" x2="12" y2="22" />
                        <polyline points="9 19 12 22 15 19" />
                     </svg>
                     {{__('Download')}}
                  </a>
                  <a id="share" href="javascript:void(0)" class="btn btn-danger ms-3">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="6" cy="12" r="3" />
                        <circle cx="18" cy="6" r="3" />
                        <circle cx="18" cy="18" r="3" />
                        <line x1="8.7" y1="10.7" x2="15.3" y2="7.3" />
                        <line x1="8.7" y1="13.3" x2="15.3" y2="16.7" />
                     </svg>
                     {{__('Share')}}
                  </a>
               </div>
               <div class="col-auto d-flex d-md-none">
                  <a href="{{ route('download.image', $image->image_id) }}" class="btn ms-3">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" />
                        <line x1="12" y1="13" x2="12" y2="22" />
                        <polyline points="9 19 12 22 15 19" />
                     </svg>
                  </a>
                  <a id="share-mobile" href="javascript:void(0)" class="btn btn-danger ms-3">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="6" cy="12" r="3" />
                        <circle cx="18" cy="6" r="3" />
                        <circle cx="18" cy="18" r="3" />
                        <line x1="8.7" y1="10.7" x2="15.3" y2="7.3" />
                        <line x1="8.7" y1="13.3" x2="15.3" y2="16.7" />
                     </svg>
                  </a>
               </div>
            </div>
         </div>
         <div class="share-buttons">
            <a class="resp-sharing-button__link" href="https://facebook.com/sharer/sharer.php?u={{ url('ib/'.$image->image_id) }}" target="_blank" rel="noopener" aria-label="">
               <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--small">
                  <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                     </svg>
                  </div>
               </div>
            </a>
            <a class="resp-sharing-button__link" href="https://twitter.com/intent/tweet/?text={{ $image->image_id }}&amp;url={{ url('ib/'.$image->image_id) }}" target="_blank" rel="noopener" aria-label="">
               <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--small">
                  <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/>
                     </svg>
                  </div>
               </div>
            </a>
            <a class="resp-sharing-button__link" href="https://www.tumblr.com/widgets/share/tool?posttype=link&amp;title={{ $image->image_id }}&amp;caption={{ $image->image_id }}&amp;content={{ url('ib/'.$image->image_id) }}&amp;canonicalUrl={{ url('ib/'.$image->image_id) }}&amp;shareSource=tumblr_share_button" target="_blank" rel="noopener" aria-label="">
               <div class="resp-sharing-button resp-sharing-button--tumblr resp-sharing-button--small">
                  <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M13.5.5v5h5v4h-5V15c0 5 3.5 4.4 6 2.8v4.4c-6.7 3.2-12 0-12-4.2V9.5h-3V6.7c1-.3 2.2-.7 3-1.3.5-.5 1-1.2 1.4-2 .3-.7.6-1.7.7-3h3.8z"/>
                     </svg>
                  </div>
               </div>
            </a>
            <a class="resp-sharing-button__link" href="https://pinterest.com/pin/create/button/?url={{ url('ib/'.$image->image_id) }}&amp;media={{ url('ib/'.$image->image_id) }}&amp;description={{ $image->image_id }}" target="_blank" rel="noopener" aria-label="">
               <div class="resp-sharing-button resp-sharing-button--pinterest resp-sharing-button--small">
                  <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12.14.5C5.86.5 2.7 5 2.7 8.75c0 2.27.86 4.3 2.7 5.05.3.12.57 0 .66-.33l.27-1.06c.1-.32.06-.44-.2-.73-.52-.62-.86-1.44-.86-2.6 0-3.33 2.5-6.32 6.5-6.32 3.55 0 5.5 2.17 5.5 5.07 0 3.8-1.7 7.02-4.2 7.02-1.37 0-2.4-1.14-2.07-2.54.4-1.68 1.16-3.48 1.16-4.7 0-1.07-.58-1.98-1.78-1.98-1.4 0-2.55 1.47-2.55 3.42 0 1.25.43 2.1.43 2.1l-1.7 7.2c-.5 2.13-.08 4.75-.04 5 .02.17.22.2.3.1.14-.18 1.82-2.26 2.4-4.33.16-.58.93-3.63.93-3.63.45.88 1.8 1.65 3.22 1.65 4.25 0 7.13-3.87 7.13-9.05C20.5 4.15 17.18.5 12.14.5z"/>
                     </svg>
                  </div>
               </div>
            </a>
            <a class="resp-sharing-button__link" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{ url('ib/'.$image->image_id) }}&amp;title={{ $image->image_id }}&amp;summary={{ $image->image_id }}&amp;source={{ url('ib/'.$image->image_id) }}" target="_blank" rel="noopener" aria-label="">
               <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--small">
                  <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/>
                     </svg>
                  </div>
               </div>
            </a>
            <a class="resp-sharing-button__link" href="whatsapp://send?text={{ $image->image_id }}%20{{ url('ib/'.$image->image_id) }}" target="_blank" rel="noopener" aria-label="">
               <div class="resp-sharing-button resp-sharing-button--whatsapp resp-sharing-button--small">
                  <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2 .5 3.9 1.5 5.6L.6 23.4l6-1.6c1.6.9 3.5 1.3 5.4 1.3 6.3 0 11.4-5.1 11.4-11.4-.1-2.8-1.2-5.7-3.3-7.8zM12 21.4c-1.7 0-3.3-.5-4.8-1.3l-.4-.2-3.5 1 1-3.4L4 17c-1-1.5-1.4-3.2-1.4-5.1 0-5.2 4.2-9.4 9.4-9.4 2.5 0 4.9 1 6.7 2.8 1.8 1.8 2.8 4.2 2.8 6.7-.1 5.2-4.3 9.4-9.5 9.4zm5.1-7.1c-.3-.1-1.7-.9-1.9-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1s-1.2-.5-2.3-1.4c-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.5.1-.6s.3-.3.4-.5c.2-.1.3-.3.4-.5.1-.2 0-.4 0-.5C10 9 9.3 7.6 9 7c-.1-.4-.4-.3-.5-.3h-.6s-.4.1-.7.3c-.3.3-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.3-.3-.4-.6-.5z"/>
                     </svg>
                  </div>
               </div>
            </a>
            <a class="resp-sharing-button__link" href="http://vk.com/share.php?title={{ $image->image_id }}&amp;url={{ url('ib/'.$image->image_id) }}" target="_blank" rel="noopener" aria-label="">
               <div class="resp-sharing-button resp-sharing-button--vk resp-sharing-button--small">
                  <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M21.547 7h-3.29a.743.743 0 0 0-.655.392s-1.312 2.416-1.734 3.23C14.734 12.813 14 12.126 14 11.11V7.603A1.104 1.104 0 0 0 12.896 6.5h-2.474a1.982 1.982 0 0 0-1.75.813s1.255-.204 1.255 1.49c0 .42.022 1.626.04 2.64a.73.73 0 0 1-1.272.503 21.54 21.54 0 0 1-2.498-4.543.693.693 0 0 0-.63-.403h-2.99a.508.508 0 0 0-.48.685C3.005 10.175 6.918 18 11.38 18h1.878a.742.742 0 0 0 .742-.742v-1.135a.73.73 0 0 1 1.23-.53l2.247 2.112a1.09 1.09 0 0 0 .746.295h2.953c1.424 0 1.424-.988.647-1.753-.546-.538-2.518-2.617-2.518-2.617a1.02 1.02 0 0 1-.078-1.323c.637-.84 1.68-2.212 2.122-2.8.603-.804 1.697-2.507.197-2.507z"/>
                     </svg>
                  </div>
               </div>
            </a>
            <a class="resp-sharing-button__link" href="https://telegram.me/share/url?text={{ $image->image_id }}&amp;url={{ url('ib/'.$image->image_id) }}" target="_blank" rel="noopener" aria-label="">
               <div class="resp-sharing-button resp-sharing-button--telegram resp-sharing-button--small">
                  <div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M.707 8.475C.275 8.64 0 9.508 0 9.508s.284.867.718 1.03l5.09 1.897 1.986 6.38a1.102 1.102 0 0 0 1.75.527l2.96-2.41a.405.405 0 0 1 .494-.013l5.34 3.87a1.1 1.1 0 0 0 1.046.135 1.1 1.1 0 0 0 .682-.803l3.91-18.795A1.102 1.102 0 0 0 22.5.075L.706 8.475z"/>
                     </svg>
                  </div>
               </div>
            </a>
         </div>
         <div class="image_inputs">
            <h2>{{__('Image link')}}</h2>
            <div class="input-group form-group">
               {{-- <input class="form-control sharelink" name="sharelink" id="sharelink" value="{{ url('ib/'.$image->image_id) }}" readonly> --}}
               <input class="form-control sharelink" name="sharelink" id="sharelink" value="{{ $image->image_path }}" readonly>
               <button id="copy" class="copy-btn btn" type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <rect x="8" y="8" width="12" height="12" rx="2" />
                     <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                  </svg>
                  <span class="copy">{{__('Copy')}}</span>
                  <span class="copied d-none">{{__('Copied')}}</span>
               </button>
            </div>
            <div class="form-group">
               <h2>{{__('HTML')}}</h2>
               <textarea class="form-control sharelink" name="html" id="html" cols="30" rows="3" readonly><a href="{{ url('ib/'.$image->image_id) }}"><img src="{{ $image->image_path ?? '' }}" alt="{{ $image->image_id }}"/></a></textarea>
            </div>
            <div class="form-group">
               <h2>{{__('BBCode')}}</h2>
               <textarea class="form-control sharelink" name="BBCode" id="BBCode" cols="30" rows="3" readonly>[url={{ url('ib/'.$image->image_id) }}][img]{{ $image->image_path ?? '' }}[/img][/url]</textarea>
            </div>
         </div>
        </div>
    </div> -->
</div>


@endsection