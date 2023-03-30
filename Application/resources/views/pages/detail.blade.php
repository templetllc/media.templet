@extends('layouts.approval')
@section('title', 'Approval Image')
@section('content')
@if($image->approval === 1)
    <span class="tag detail-tag">Approved</span>
@endif
@if($image->approval === 0)
    <span class="tag tag-unapproved detail-tag">Unapproved</span>
@endif
<div class="imgbob_dash image-detail-container">
    <div class="content" data-base="">
        <!-- Gallery Wrapper : BEGIN -->
        <section class="gallery-wrapper-detail py-4 mt-4" data-id="{{ $image->id }}">
            <div class="container-fluid">
                <div class="row d-flex align-items-center justify-content-between mx-0">
                    <div class="col-2 p-0">
                        @if($prev_image)
                            <a href="{{ route('approvals.detail', array_merge(array($type, $prev_image->id, $status), ['page' => $prev_image_page])) }}" class="btn btn-link p-0">
                                <svg id="chevron" xmlns="http://www.w3.org/2000/svg" width="39" height="39" viewBox="0 0 39 39">
                                    <rect id="bg" width="39" height="39" rx="19.5" fill="#e3e3e5"/>
                                    <g id="chevron-left" transform="translate(15.803 13)">
                                    <path id="icons_chevron-left" data-name="icons / chevron-left" d="M22.488,25.244l-5.632-5.632a.867.867,0,0,1-.2-.284.889.889,0,0,1,0-.626.867.867,0,0,1,.2-.284l5.661-5.661a.859.859,0,0,1,1.223,0,.8.8,0,0,1,.242.626.9.9,0,0,1-.27.626l-5.007,5.007,5.035,5.035a.825.825,0,0,1,0,1.195.893.893,0,0,1-1.252,0Z" transform="translate(-16.6 -12.5)" fill="#999da7"/>
                                    </g>
                                </svg>
                            </a>
                        @endif
                    </div>
                    <div class="col-7">
                        <div class="img-item mb-2 text-center d-flex justify-content-center">
                            <div class="overflow-hidden position-relative">
                                <div class="skeleton"></div>
                                <img id="img_{{ $image->id }}" src="{{ $image->image_path }}" class="lazy img-fluid img rounded-1"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-2 p-0">
                        @if($next_image)
                        <a href="{{ route('approvals.detail', array_merge(array($type, $next_image->id, $status), ['page' => $next_image_page])) }}" class="btn btn-link float-end p-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="39" height="39" viewBox="0 0 39 39">
                                <g id="chevron" transform="translate(39 39) rotate(180)">
                                  <rect id="bg" width="39" height="39" rx="19.5" fill="#e3e3e5"/>
                                  <g id="chevron-left" transform="translate(15.803 13)">
                                    <path id="icons_chevron-left" data-name="icons / chevron-left" d="M22.488,25.244l-5.632-5.632a.867.867,0,0,1-.2-.284.889.889,0,0,1,0-.626.867.867,0,0,1,.2-.284l5.661-5.661a.859.859,0,0,1,1.223,0,.8.8,0,0,1,.242.626.9.9,0,0,1-.27.626l-5.007,5.007,5.035,5.035a.825.825,0,0,1,0,1.195.893.893,0,0,1-1.252,0Z" transform="translate(-16.6 -12.5)" fill="#999da7"/>
                                  </g>
                                </g>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- Gallery Wrapper : END -->

        <!-- Menu : BEGIN -->
        <nav class="bottom-menu fixed-bottom bg-white border-top">
            <div class="container-fluid">
                <div class="row align-items-center h-70 mx-0">
                    <div class="col-auto me-auto">
                        <p class="mb-0">
                            <span>{{$index}}/<span class="NexaLight">{{$total}}</span></span>
                            <span>&nbsp;&nbsp;|&nbsp;&nbsp;{{ $image->category }}</span>
                        </p>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-link btn-badge p-0 icon-btn" data-top-toggle="tooltip" title="Feedback" data-action="feedback">
                                <svg id="comment" xmlns="http://www.w3.org/2000/svg" width="21" height="19.613" viewBox="0 0 21 19.613">
                                    <path id="stroke" d="M8.468,15.815H14.9a.708.708,0,0,0,.519-.215.76.76,0,0,0-.545-1.3H8.442a.708.708,0,0,0-.519.215.76.76,0,0,0,.545,1.3Zm0-3.293H19.132a.708.708,0,0,0,.519-.215.76.76,0,0,0-.545-1.3H8.442a.708.708,0,0,0-.519.215.76.76,0,0,0,.545,1.3Zm0-3.293H19.132a.708.708,0,0,0,.519-.215.76.76,0,0,0-.545-1.3H8.442a.708.708,0,0,0-.519.215.76.76,0,0,0,.545,1.3ZM3.3,21.843V5.277a1.964,1.964,0,0,1,.6-1.419A1.9,1.9,0,0,1,5.3,3.25H22.273a1.944,1.944,0,0,1,1.419.608A1.944,1.944,0,0,1,24.3,5.277V18.2a1.917,1.917,0,0,1-.608,1.393,1.944,1.944,0,0,1-1.419.608H7.353L5,22.553a.908.908,0,0,1-1.089.215A.936.936,0,0,1,3.3,21.843Zm2-2.128,1.52-1.52H22.273V5.277H5.3Zm0-14.439v0Z" transform="translate(-3.3 -3.25)" fill="#232e3c"/>
                                </svg>
                            </button>
                            <a class="btn btn-link ms-3 p-0 icon-btn" href="{{ route('approvals', array_merge(array($type, $status), ['page' => $page])) }}" data-top-toggle="tooltip" title="Gallery">
                                <svg id="home" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                    <path id="view_cozy_FILL0_wght600_GRAD0_opsz48" d="M12.053,14.206H7.83a2.186,2.186,0,0,1-2.18-2.18V7.8A2.068,2.068,0,0,1,6.29,6.29a2.1,2.1,0,0,1,1.54-.64h4.223A2.187,2.187,0,0,1,14.206,7.8v4.223a2.1,2.1,0,0,1-.64,1.54A2.068,2.068,0,0,1,12.053,14.206Zm-4.36-2.044h4.469V7.694H7.694Zm4.36,13.488H7.83a2.186,2.186,0,0,1-2.18-2.18V19.247a2.068,2.068,0,0,1,.64-1.512,2.1,2.1,0,0,1,1.54-.64h4.223a2.187,2.187,0,0,1,2.153,2.153V23.47a2.1,2.1,0,0,1-.64,1.54A2.068,2.068,0,0,1,12.053,25.65Zm-4.36-2.044h4.469V19.138H7.694Zm15.8-9.4H19.274a2.186,2.186,0,0,1-2.18-2.18V7.8a2.068,2.068,0,0,1,.64-1.512,2.1,2.1,0,0,1,1.54-.64H23.5A2.187,2.187,0,0,1,25.65,7.8v4.223a2.1,2.1,0,0,1-.64,1.54,2.068,2.068,0,0,1-1.512.64Zm-4.36-2.044h4.469V7.694H19.138ZM23.5,25.65H19.274a2.186,2.186,0,0,1-2.18-2.18V19.247a2.068,2.068,0,0,1,.64-1.512,2.1,2.1,0,0,1,1.54-.64H23.5a2.187,2.187,0,0,1,2.153,2.153V23.47a2.1,2.1,0,0,1-.64,1.54A2.068,2.068,0,0,1,23.5,25.65Zm-4.36-2.044h4.469V19.138H19.138ZM12.162,12.162ZM12.162,19.138ZM19.138,12.162ZM19.138,19.138Z" transform="translate(-5.65 -5.65)" fill="#232e3c"/>
                                </svg>
                            </a>
                            <button class="btn btn-link ms-3 p-0 icon-btn" data-top-toggle="tooltip" title="Share" data-action="copyDetailImageUrl">
                                <svg id="link" xmlns="http://www.w3.org/2000/svg" width="24" height="12.42" viewBox="0 0 24 12.42">
                                    <path id="stroke" d="M9.5,25.67a6.083,6.083,0,0,1-6.2-6.2,6.043,6.043,0,0,1,1.78-4.429A5.97,5.97,0,0,1,9.5,13.25h3.59a1.1,1.1,0,0,1,.825.347,1.169,1.169,0,0,1,.333.84,1.086,1.086,0,0,1-.333.8,1.121,1.121,0,0,1-.825.333H9.5a3.81,3.81,0,0,0-3.908,3.908A3.81,3.81,0,0,0,9.5,23.383h3.59a1.137,1.137,0,0,1,1.158,1.158,1.086,1.086,0,0,1-.333.8,1.121,1.121,0,0,1-.825.333Zm2-5.24a.989.989,0,0,1-.709-.261,1.015,1.015,0,0,1,0-1.39.989.989,0,0,1,.709-.261h7.585a.989.989,0,0,1,.709.261,1.015,1.015,0,0,1,0,1.39.989.989,0,0,1-.709.261Zm5.993,5.24a1.137,1.137,0,0,1-1.158-1.158,1.086,1.086,0,0,1,.333-.8,1.12,1.12,0,0,1,.825-.333h3.59a3.81,3.81,0,0,0,3.908-3.908,3.81,3.81,0,0,0-3.908-3.908h-3.59a1.137,1.137,0,0,1-1.158-1.158,1.135,1.135,0,0,1,.333-.811,1.1,1.1,0,0,1,.825-.347h3.59A6.131,6.131,0,0,1,27.3,19.474a5.97,5.97,0,0,1-1.795,4.415,6.044,6.044,0,0,1-4.429,1.78Z" transform="translate(-3.3 -13.25)" fill="#232e3c"/>
                                </svg>
                            </button>
                            <a href="#" class="btn btn-outline-primary ms-3" id="{{ $image->id }}" data-toggle="unapprovalDetail">Unapproved</a>
                            <a href="#" class="btn btn-primary ms-2" id="{{ $image->id }}" data-toggle="approvalDetail">Approved</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Menu : END -->
    </div>
</div>
<script type="text/javascript">

    window.onload = function(){
        $('body').find('.media-item.loading').each(function(){
            $(this).removeClass('loading');
        });

        var _imageId = $('.gallery-wrapper-detail').data('id');
        let feedback = new feedbackClass(_imageId+'.json');
        feedback.init({
            offsetTop: "110",
            path: "/assets/"
        });
    };

</script>
@endsection
