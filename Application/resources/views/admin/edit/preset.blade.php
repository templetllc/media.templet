@extends('layouts.admin')
@section('title', 'Manage presets')
@section('content')
<div class="note note-danger print-error-msg" style="display:none"><span></span></div>
<div class="card">
    <div class="card-body">
        <form id="editPresetForm" method="POST">
            @csrf
            <input type="hidden" name="preset_id" id="preset_id" value="{{ $preset->id }}">
            <div class="form-group">
                <label for="preset">{{__('Preset :')}}</label>
                <input type="text" id="preset" class="form-control fm40" placeholder="Preset name" name="preset" required value="{{ $preset->preset }}">
            </div>
            <div class="form-group">
                <label for="width">{{__('Width(px) :')}}</label>
                <input type="number" id="width" class="form-control fm40" placeholder="Width preset" name="width" required value="{{ $preset->width }}">
            </div>
            <div class="form-group">
                <label for="height">{{__('Height(px) :')}}</label>
                <input type="number" id="height" class="form-control fm40" placeholder="Height preset" name="height" required value="{{ $preset->height }}">
            </div>
            <div class="form-group">
                <label for="content">{{__('Description :')}}</label>
                <textarea id="content" class="form-control" rows="3" placeholder="Description of preset" name="content">{{ $preset->content }}</textarea> 
            </div>
            <div class="form-group">
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="active" id="active" {{ $preset->active ?? old('active') === 1 ? 'checked="checked"' : '' }}>
                    <span class="form-check-label">{{__('Active')}}</span>
                </label>
            </div>
            <button class="editPresetBtn btn btn-primary" id="editPresetBtn">{{__('Save changes')}}</button>
        </form>
    </div>
</div>
@endsection