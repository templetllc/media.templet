@extends('layouts.admin')
@section('title', 'Uploads')
@section('content')
<div class="card mb-2 d-block d-md-none">
   <div class="card-body">
      <form action="{{ route('uploads') }}" method="GET">
         <div class="input-icon">
            <span class="input-icon-addon">
               <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <circle cx="10" cy="10" r="7"></circle>
                  <line x1="21" y1="21" x2="15" y2="15"></line>
               </svg>
            </span>
            <input type="text" name="q" class="form-control" placeholder="Search on uploads...">
         </div>
      </form>
   </div>
</div>
<div class="card">
   <div class="card-header">
      <h2 class="m-0">{{__('All uploads')}}</h2>
   </div>
   <div class="card-body">
      @if($uploads->count() > 0)
      <div class="table-responsive">
         <table class="display table table-striped table-bordered" >
            <thead>
               <tr>
                  <th class="text-center">{{__('#ID')}}</th>
                  <th class="text-center">{{__('User name')}}</th>
                  <th class="text-center">{{__('Image preview')}}</th>
                  <th class="text-center">{{__('Image ID')}}</th>
                  <th class="text-center">{{__('Image Size')}}</th>
                  <th class="text-center">{{__('Uploaded at')}}</th>
                  <th class="text-center">{{__('View / Delete')}}</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($uploads as $upload)
               <tr class="image-table{{ $upload->id }}">
                  <td class="text-center">{{ $upload->id }}</td>
                  <td class="text-center">
                     @if($upload->user_id != null)
                     <a href="{{ route('view.user', $upload->user->id) }}">{{ $upload->user->name }}</a>
                     @else 
                     {{__('Anonymous')}}
                     @endif
                  </td>
                  <td class="text-center">
                     <img src="{{ $upload->image_path }}" alt="{{ $upload->image_id }}" width="50" height="50">
                  </td>
                  <td class="text-center">{{ $upload->image_id }}</td>
                  <td class="text-center">{{ formatBytes($upload->image_size) }}</td>
                  <td class="text-center">{{ date("d/m/y  H:i A", strtotime($upload->created_at))}}</td>
                  <td class="text-center">
                     <a href="{{ route('view.image', $upload->id) }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                           <circle cx="12" cy="12" r="2" />
                           <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                        </svg>
                     </a>
                     <a href="#" data-id="{{ $upload->id }}" id="deleteUpload" class="btn btn-danger btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                           <line x1="4" y1="7" x2="20" y2="7" />
                           <line x1="10" y1="11" x2="10" y2="17" />
                           <line x1="14" y1="11" x2="14" y2="17" />
                           <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                           <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                     </a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
      @if(\Request::input('q') == null)
      {{$uploads->links()}}
      @endif
      @else 
      <div class="empty">
         <div class="empty-img">
            <img src="{{ asset('images/sections/empty.svg') }}" height="128" alt="">
         </div>
         <p class="empty-title">{{__('No data found')}}</p>
         <p class="empty-subtitle text-muted">
            {{__('This section is empty and has no content.')}}
         </p>
      </div>
      @endif
   </div>
</div>
@endsection