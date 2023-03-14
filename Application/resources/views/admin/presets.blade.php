@extends('layouts.admin')
@section('title', 'Manage presets')
@section('content')
<div class="card">
   <div class="card-header">
      <h2 class="m-0">{{__('All presets')}}</h2>
      <span class="col-auto ms-auto d-print-none">
         <button data-bs-toggle="modal" data-bs-target="#modal-simple" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h6m-3 -3v6" /></svg>
            {{__('Add Preset')}}
         </button>
      </span>
      <div class="modal modal-blur fade" id="modal-simple" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">{{__('Add new preset')}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <form id="addPresetForm" method="POST">
                     <div class="note note-danger print-error-msg" style="display:none"><span></span></div>
                        @csrf
                        <div class="form-group">
                           <label for="preset">{{__('Preset :')}}</label>
                           <input type="text" id="preset" class="form-control fm40" placeholder="Preset name" name="preset" required>
                        </div>
                        <div class="form-group">
                           <label for="width">{{__('Width(px) :')}}</label>
                           <input type="number" id="width" class="form-control fm40" placeholder="Width preset" name="width" required>
                        </div>
                        <div class="form-group">
                           <label for="height">{{__('Height(px) :')}}</label>
                           <input type="number" id="height" class="form-control fm40" placeholder="Height preset" name="height" required>
                        </div>
                        <div class="form-group">
                           <label for="content">{{__('Description :')}}</label>
                           <textarea id="content" class="form-control" rows="3" placeholder="Description of preset" name="content"></textarea> 
                        </div>
                        <div class="form-group">
                           <label class="form-check">
                              <input class="form-check-input" type="checkbox" name="active" id="active" {{ old('active') ? 'checked' : '' }}>
                              <span class="form-check-label">{{__('Active')}}</span>
                           </label>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button id="addPreset" type="type" class="btnadd btn btn-primary">{{__('Add')}}</button>
                     </div>
                  </form>
            </div>
         </div>
      </div>
   </div>
   <div class="card-body">
      <div class="table-responsive">
         <table id="basic-datatables" class="display table table-striped table-bordered" >
            <thead>
               <tr>
                  <th class="text-center">{{__('#ID')}}</th>
                  <th class="text-center">{{__('Preset')}}</th>
                  <th class="text-center">{{__('Width')}}</th>
                  <th class="text-center">{{__('Height')}}</th>
                  <th class="text-center">{{__('Description')}}</th>
                  <th class="text-center">{{__('Status')}}</th>
                  <th class="text-center">{{__('Edit / Delete')}}</th>
               </tr>
            </thead>
            <tbody>
               @foreach($presets as $preset)
               <tr>
                  <td class="text-center">{{ $preset->id }}</td>
                  <td class="text-center">{{ $preset->preset }}</td>
                  <td class="text-center">{{ $preset->width }}</td>
                  <td class="text-center">{{ $preset->height }}</td>
                  <td class="text-center">{{ $preset->content }}</td>
                  <td class="text-center">
                     @if($preset->active == 0)
                        <span class="badge bg-secondary">{{__('No Active')}}</span>
                     @elseif($preset->active == 1)
                        <span class="badge bg-primary">{{__('Active')}}</span>
                     @endif
                  </td>
                  <td class="text-center">
                     <a href="{{ route('preset.edit', $preset->id) }}" class="btn btn-info btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>
                     </a>
                     <a href="#" data-id="{{ $preset->id }}" id="deletePreset" class="btn btn-danger btn-sm">
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
   </div>
</div>
@endsection