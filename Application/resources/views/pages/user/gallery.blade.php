@extends('layouts.user')
@section('title', 'My Gallery')
@section('content')
<div class="imgbob_dash mt-3">
    <div class="content" data-base="/user/gallery">

        @include('includes.filter')

        @if($type == 0)
            @include('pages.gallery.masonry')
        @else
            @include('pages.gallery.thumbnails')
        @endif

        <div class="d-flex justify-content-center tags-container mt-5">
            <ul>
                @foreach($tags as $tag)
                    <li class="item-tag {{(app('request')->input('t')==$tag) ? 'active':''}}" role="button">
                        {{ $tag }}
                    </li>
                @endforeach
            </ul>
        </div>

        {{$images->links()}}
        <div class="empty empty-gallery @if($images->count() != 0) d-none  @endif">
            <div class="empty-img">
                <img src="{{ asset('images/sections/empty.svg') }}" height="128" alt="">
            </div>
            <p class="empty-title">{{__('No images found')}}</p>
            @if(userHasRole(Auth()->user()->permission, array(ADMIN_ROLE, CONTRIBUTOR_ROLE, MANAGER_ROLE, APPROVER_ROLE)))
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
            @endif
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    window.onload = function(){
        $('body').find('.media-item.loading').each(function(){
            $(this).removeClass('loading');
        });
    };
</script>
@endsection
