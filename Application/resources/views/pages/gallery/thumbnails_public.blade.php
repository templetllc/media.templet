<div class="gallery-thumbnails ">
<div class="row row-cards images-container">
    @foreach ($images as $image)
    <div class="col-6 col-sm-3 col-md-2 col-lg-1">
        <div class="item-image image{{ $image->id }}" data-category="{{ empty($image->category) ? 'uncategorized': str_replace(" ","_",strtolower($image->category)) }}" data-preset="{{ empty($image->preset) ? 'original_size': $image->preset }}" data-tags="{{ $image->tags }}" data-date="{{ $image->created_at->format('Y').'_'.$image->created_at->format('n') }}">
            <a href="{{ route('preview.view', $image->image_id) }}" target="">
                <img src="{{ $image->image_path }}" class="thumbnail-image">
                <div class="overlay">
                    <input type="text" id="{{ $image->id }}" value="{{ $image->image_path }}" style="z-index: -99; opacity: 0; position: absolute; width: 100%;">
                    <div class="icon-view">
                        <button class="text-muted border-0 bg-transparent">
                             <svg xmlns="http://www.w3.org/2000/svg" width="19.72" height="19.72" viewBox="0 0 19.72 19.72">
                             <g id="Icon_feather-search" data-name="Icon feather-search" transform="translate(-14.53 248.603)">
                             <path id="Union_16" data-name="Union 16" d="M1665.72-491.53a.747.747,0,0,1-.53-.22l-3.989-3.988a8.723,8.723,0,0,1-5.662,2.066,8.8,8.8,0,0,1-8.789-8.789,8.8,8.8,0,0,1,8.789-8.789,8.8,8.8,0,0,1,8.789,8.789,8.723,8.723,0,0,1-2.066,5.662l3.989,3.988a.748.748,0,0,1,.22.53.748.748,0,0,1-.22.53A.748.748,0,0,1,1665.72-491.53Zm-10.181-18.22a7.3,7.3,0,0,0-7.289,7.289,7.3,7.3,0,0,0,7.289,7.289,7.241,7.241,0,0,0,5.154-2.135,7.241,7.241,0,0,0,2.135-5.154A7.3,7.3,0,0,0,1655.539-509.75Z" transform="translate(-1632.22 262.647)" fill="#fff"/>
                             </g>
                             </svg>
                        </button>
                    </div>
                    <div class="icon-action">
                        <button data-id="{{ $image->id }}" class="ico-copy">
                            <svg id="ico-copy" xmlns="http://www.w3.org/2000/svg" width="21.917" height="21.917" viewBox="0 0 21.917 21.917">
                            <path id="Trazado_25" data-name="Trazado 25" d="M0,0H21.917V21.917H0Z" fill="none"/>
                            <path id="Unión_4" data-name="Unión 4" d="M-288.153-614.653a2.5,2.5,0,0,1-2.5-2.5v-.889h-1.021A2.329,2.329,0,0,1-294-620.368v-7.305A2.33,2.33,0,0,1-291.674-630h7.306a2.329,2.329,0,0,1,2.326,2.326v1.021h.889a2.5,2.5,0,0,1,2.5,2.5v7a2.5,2.5,0,0,1-2.5,2.5Zm-1.5-9.5v7a1.5,1.5,0,0,0,1.5,1.5h7a1.5,1.5,0,0,0,1.5-1.5v-7a1.5,1.5,0,0,0-1.5-1.5h-7A1.5,1.5,0,0,0-289.652-624.153ZM-293-627.674v7.305a1.328,1.328,0,0,0,1.327,1.327h1.021v-5.111a2.5,2.5,0,0,1,2.5-2.5h5.111v-1.021A1.328,1.328,0,0,0-284.368-629h-7.306A1.328,1.328,0,0,0-293-627.674Z" transform="translate(297.153 633.153)" fill="#fff"/>
                            </svg>
                        </button>

                        <button data-url="{{ route('download.public', $image->image_id) }}" class="btn-download pe-1">
                            <svg id="ico-download" xmlns="http://www.w3.org/2000/svg" width="21.917" height="21.917" viewBox="0 0 21.917 21.917">
                            <path id="Trazado_27" data-name="Trazado 27" d="M0,0H21.917V21.917H0Z" fill="rgba(0,0,0,0)"/>
                            <path id="Unión_6" data-name="Unión 6" d="M-316.911-613.576l-2.74-2.74a.5.5,0,0,1,0-.707.5.5,0,0,1,.706,0l1.879,1.878v-6.875a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v6.89l1.894-1.894a.5.5,0,0,1,.707,0,.5.5,0,0,1,0,.707l-2.74,2.74a.5.5,0,0,1-.353.146A.5.5,0,0,1-316.911-613.576Zm6.246-4.007a.5.5,0,0,1,.5-.5,2.677,2.677,0,0,0,1.906-.79,2.678,2.678,0,0,0,.79-1.906,2.7,2.7,0,0,0-2.7-2.7h-.913a.5.5,0,0,1-.39-.187.5.5,0,0,1-.1-.422,3.715,3.715,0,0,0-.549-2.888,4.654,4.654,0,0,0-3.06-1.944,4.636,4.636,0,0,0-5.46,3.225.5.5,0,0,1-.508.39,3.684,3.684,0,0,0-3.757,2.7,3.48,3.48,0,0,0,2.058,4.011.5.5,0,0,1,.259.658.5.5,0,0,1-.659.259,4.478,4.478,0,0,1-2.629-5.17,4.669,4.669,0,0,1,4.364-3.458,5.7,5.7,0,0,1,6.51-3.6,5.652,5.652,0,0,1,3.714,2.375,4.73,4.73,0,0,1,.788,3.05h.329a3.7,3.7,0,0,1,3.7,3.7,3.671,3.671,0,0,1-1.083,2.614,3.671,3.671,0,0,1-2.614,1.083A.5.5,0,0,1-310.665-617.583Z" transform="translate(327.516 632.673)" fill="#fff"/>
                            </svg>
                        </button>
                    </div>
                </div>  
            </a>
        </div>
    </div>
    @endforeach
</div>
</div>