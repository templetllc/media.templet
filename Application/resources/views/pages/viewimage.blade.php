@extends('layouts.pages')
@section('title', 'Image - '.$image->image_id)

@section('content')
<div class="view_image_page pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 m-auto text-center">
                @if($image->user_id == Auth()->user()->id || Auth()->user()->permission == ADMIN_ROLE)
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
                                @if(userHasRole(Auth()->user()->permission, array(ADMIN_ROLE, MANAGER_ROLE)))
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
                                @endif
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
</div>
@endsection
