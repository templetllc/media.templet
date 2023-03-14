@extends('layouts.admin')
@section('title', 'View user #'.$user->id)
@section('content')
<div class="card mb-3">
    <div class="card-header"><h2 class="m-0">{{__('User information')}}</h2>
        <span class="col-auto ms-auto d-print-none">
         {{__('Joined at :')}} {{ date("d/m/y  H:i A", strtotime($user->created_at))}}
         </span>
    </div>
    <div class="card-body text-center">
        <img class="rounded-circle mb-3" src="{{ asset('path/cdn/avatars/'. $user->avatar) }}" alt="" width="150" height="150">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="note note-danger print-error-msg" style="display:none"><span></span></div>
                <form id="editUserForm" method="POST">
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="form-group">
                        <label for="name">{{__('User Name :')}} <span class="fsgred">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter user name" value="{{ $user->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">{{__('Email :')}} <span class="fsgred">*</span></label>
                        <div class="input-group">
                            <input type="text" name="email" id="email" class="remove-spaces form-control" placeholder="email" value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('Status :')}} <span class="fsgred">*</span></label>
                        <div class="input-group">
                            <select name="status" id="status" class="remove-spaces form-control">
                                <option value="1" {{ ($user->status == 1) ? 'selected':'' }}>Active</option>
                                <option value="2" {{ ($user->status == 2) ? 'selected':'' }}>Banned</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="permission">{{__('Permission :')}} <span class="fsgred">*</span></label>
                        <div class="input-group">
                            <select name="permission" id="permission" class="remove-spaces form-control">
                                <option value="1" {{ ($user->permission == 1) ? 'selected':'' }}>Admin</option>
                                <option value="2" {{ ($user->permission == 2) ? 'selected':'' }}>User</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category">{{__('Category :')}} <span class="fsgred">*</span></label>
                        <div class="input-group">
                            <select name="category" id="category" class="remove-spaces form-control">
                                <option value=""> </option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($user->category == $category->id) ? 'selected':'' }}>{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="editUserBtn btn btn-primary" id="editUserBtn">{{__('Save changes')}}</button>
              </form>
            </div>
        </div>
        
    </div>
</div>
@endsection