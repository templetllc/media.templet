@extends('layouts.admin')
@section('title', "View Image #".$image->image_id)
@section('content')
<div class="view_image">
   <div class="row">
      <div class="col-lg-8 mb-3 text-center">
         <img src="{{ $image->image_path }}" class="img-fluid">
      </div>
      <div class="col-lg-4">
         <div class="card">
            <div class="card-header">{{__('Image & user information')}}</div>
            <div class="card-body">
               <div class="text-center">
                  @if($image->user_id != null)
                  <img class="rounded-circle mb-3" src="{{ asset('path/cdn/avatars/'. $image->user->avatar) }}" alt="" width="110" height="110">
                  <h1>{{ $image->user->name }}</h1>
                  <h3 class="text-muted">{{ $image->user->email }}</h3>
                  <h3>
                     @if($image->user->status == 2)
                     <span class="badge bg-danger">{{__('Banned')}}</span>
                     @elseif($image->user->status == 1)
                     <span class="badge bg-success">{{__('Active')}}</span>
                     @endif
                     @if($image->user->permission == 2)
                     <span class="badge bg-secondary">{{__('User')}}</span>
                     @elseif($image->user->permission == 1)
                     <span class="badge bg-primary">{{__('Admin')}}</span>
                     @endif
                  </h3>
                  @else 
                  <img class="rounded-circle mb-3" src="{{ asset('path/cdn/avatars/default.png') }}" alt="" width="110" height="110">
                  <h1>{{__('Anonymous')}}</h1>
                  @endif
               </div>
               <hr class="mb-3"/>
               <div class="divide-y-4">
                  <div class="image-info">
                     <div class="row">
                        <div class="col">
                           <div class="text-truncate">
                              <strong>{{__('Image ID :')}}</strong> {{ $image->image_id }}
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="image-info">
                     <div class="row">
                        <div class="col">
                           <div class="text-truncate">
                              <strong>{{__('Image size :')}}</strong> {{ formatBytes($image->image_size) }}
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="image-info">
                     <div class="row">
                        <div class="col">
                           <div class="text-truncate">
                              <strong>{{__('Uploaded at :')}}</strong> {{ date("d/m/y  H:i A", strtotime($image->created_at))}}
                           </div>
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