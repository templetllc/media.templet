@extends('layouts.admin')
@section('title', 'Manage categories')
@section('content')
<div class="note note-danger print-error-msg" style="display:none"><span></span></div>
<div class="card">
    <div class="card-body">
        <form id="editCategoryForm" method="POST">
            @csrf
            <input type="hidden" name="category_id" id="category_id" value="{{ $category->id }}">
            <div class="form-group">
                <label for="category">{{__('Category :')}}</label>
                <input type="text" id="category" class="form-control fm40" placeholder="Category name" name="category" required value="{{ $category->category }}">
            </div>
           

            <div class="form-group">
                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="active" id="active" {{ $category->active ?? old('active') === 1 ? 'checked="checked"' : '' }}>
                    <span class="form-check-label">{{__('Active')}}</span>
                </label>

                <label class="form-check">
                    <input class="form-check-input" type="checkbox" name="featured" id="featured" {{ $category->featured ?? old('featured') === 1 ? 'checked="checked"' : '' }}>
                    <span class="form-check-label">{{__('Featured')}}</span>
                </label>
            </div>
            <button class="editCategoryBtn btn btn-primary" id="editCategoryBtn">{{__('Save changes')}}</button>
        </form>
    </div>
</div>
@endsection