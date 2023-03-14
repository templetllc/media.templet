@extends('layouts.pages')
@section('title', 'Image - '.$image->image_id)

@section('content')
<div class="view_image_page pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 m-auto text-center">
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
                                <a href="{{ route('download.public', $image->image_id) }}" class="btn btn-primary ms-3 pull-right">
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
            </div>
        </div>
    </div>
</div>

@endsection