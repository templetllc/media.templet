@extends('layouts.admin')
@section('title', 'Website ADS')
@section('content')
<div class="amazons3_page">
   <div class="note note-danger print-error-msg mt-3" style="display:none"><span></span></div>
   <div class="card">
      <div class="card-header bg-info text-white">
         <h3 class="m-0">{{__('Manage website ads')}}</h3>
      </div>
      <div class="card-body">
         <form id="adsForm" method="POST">
            <div class="row">
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="home_ads_top">{{__('Home page top ads:')}}</label>
                     <textarea class="form-control" id="home_ads_top" name="home_ads_top" rows="10">{{ $ads->home_ads_top ?? "" }}</textarea>
                     <small class="text-muted">{{__('Use Synchronous ad and it will be showing (728x90 On Desktop) and (300x280 On Mobile)')}}</small>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="home_ads_bottom">{{__('Home page bottom ads :')}}</label>
                     <textarea class="form-control" id="home_ads_bottom" name="home_ads_bottom" rows="10">{{ $ads->home_ads_bottom ?? "" }}</textarea>
                     <small class="text-muted">{{__('Use Synchronous ad and it will be showing (982x280 On Desktop) and (300x280 On Mobile)')}}</small>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="mobile_ads">{{__('Mobile Ads :')}}</label>
                     <textarea class="form-control" id="mobile_ads" name="mobile_ads" rows="10">{{ $ads->mobile_ads ?? "" }}</textarea>
                     <small class="text-muted">{{__('Head code')}}</small>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <label for="user_account_ads">{{__('User account ads :')}}</label>
                     <textarea class="form-control" id="user_account_ads" name="user_account_ads" rows="10">{{ $ads->user_account_ads ?? "" }}</textarea>
                     <small class="text-muted">{{__('Use Synchronous ad and it will be showing (728x90 On Desktop) and (300x280 On Mobile)')}}</small>
                  </div>
               </div>
            </div>
            <button class="btnAds btn btn-primary" id="saveAdsBtn">{{__('Save changes')}}</button>
         </form>
      </div>
   </div>
</div>
@endsection