@extends('layouts.no-category')
@section('title', '')
@section('content')
<div class="empty empty-gallery">
    <div class="empty-img">
        <img src="{{ asset('images/sections/empty.svg') }}" height="128" alt="">
    </div>
    <p class="empty-title">{{__('It looks like you do not have an assigned category.')}}</p>
    <p class="empty-subtitle text-muted">
        {{__('Wait for them to assign your user a category or contact support.')}}
    </p>
</div>
@endsection
