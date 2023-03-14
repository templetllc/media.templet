@extends('layouts.landing')
@section('title', $seo->seo_title)
@section('content')
<div class="container">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-10 col-sm-8 col-lg-6">
            <div class="image-box">
                <img src="{{ asset('assets/images/img-header.png') }}" class="d-block mx-lg-auto img-fluid" alt="" width="700" height="500" loading="lazy">
            </div>
        </div>
        <div class="col-lg-6">
            <h1 class="display-5 fw-bold lh-1 mb-3">Find the perfect stock content for your next creative project<span class="point">.</span></h1>
            <p class="lead mb-4">You are going to need some login user information, though.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <button type="button" class="btn btn-secondary btn-lg px-4 me-md-2 shadow" onclick="location.href='mailto:daniel@templet.io';">Request Access</button>
            </div>
        </div>
    </div>
</div>
@endsection