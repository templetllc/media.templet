<div class="gallery-featured ">
    <div class="images-container">
        @foreach ($featured_categories as $featured_category)
            <h2 class="pb-1 pt-3">{{ $featured_category->category }}</h2>
            <div class="row">
                @foreach($featured_images as $featured_image)
                    @if($featured_image['category'] == $featured_category->category)
                    <div class="col-md-2 col-sm-3">
                        <div class="featured-item loading" data-category="{{ $featured_category->category }}">
                            <a href="{{ url('ib/'.$featured_image['image_id']) }}">
                                @php 
                                    $image_name = substr(strrchr($featured_image['image_path'], "/"), 1);
                                @endphp
                                @if(file_exists('ib/thumbnails/thumb_'.$image_name)) 
                                    <img class="lazy" data-src="{{ url('ib/thumbnails/thumb_'.$image_name) }}" />
                                @else 
                                   <img class="lazy" data-src=" {{ $featured_image['image_path'] }}" />
                                @endif
                                {{-- <div class="overlay" title="" data-toggle="tooltip" data-bs-original-title="{{ $featured_image['description'] }}">   
                                    <input type="text" id="49" value="http://media.test/ib/X0CVUynQzf.jpg" style="z-index: -99; opacity: 0; position: absolute;">   
                                    <div class="icon-view">       
                                        <button class="border-0 bg-transparent">            
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19.72" height="19.72" viewBox="0 0 19.72 19.72">
                                            <g id="Icon_feather-search" data-name="Icon feather-search" transform="translate(-14.53 248.603)">
                                                <path id="Union_16" data-name="Union 16" d="M1665.72-491.53a.747.747,0,0,1-.53-.22l-3.989-3.988a8.723,8.723,0,0,1-5.662,2.066,8.8,8.8,0,0,1-8.789-8.789,8.8,8.8,0,0,1,8.789-8.789,8.8,8.8,0,0,1,8.789,8.789,8.723,8.723,0,0,1-2.066,5.662l3.989,3.988a.748.748,0,0,1,.22.53.748.748,0,0,1-.22.53A.748.748,0,0,1,1665.72-491.53Zm-10.181-18.22a7.3,7.3,0,0,0-7.289,7.289,7.3,7.3,0,0,0,7.289,7.289,7.241,7.241,0,0,0,5.154-2.135,7.241,7.241,0,0,0,2.135-5.154A7.3,7.3,0,0,0,1655.539-509.75Z" transform="translate(-1632.22 262.647)" fill="#fff"></path>
                                            </g>
                                        </svg>
                                        </button>
                                    </div>
                                </div> --}}
                            </a>
                        </div>
                    </div>
                    @endif
                @endforeach
                <div class="col-md-2 see-more">
                    <a href="?c={{ $featured_category->id }}&g=1">
                        <img src="{{ asset('assets/images/btn-seeallgallery.svg') }}" alt="">
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>