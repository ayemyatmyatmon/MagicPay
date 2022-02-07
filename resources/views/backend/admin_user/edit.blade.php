@extends('backend.layouts.app')
@section('title','Admin_Users_Edit')
@section('admin_user','mm-active')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-display2 icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>@yield('title')

            </div>
        </div>

    </div>
</div>
<a href="#" class="btn theme mb-3"  > <i class="fas fa-plus-circle"> Edit Admin_User</i></a>

<div class="card">
    <div class="card-body">
        @include('backend.layouts.flash')
        <form action="{{route('admin.admin-user.update',$adminuser->id)}}" method="POST" id="update">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>User Name</label>
                <input class="form-control" type="text" name="name" value="{{$adminuser->name}}">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="{{$adminuser->email}}">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input class="form-control" type="number" name="phone" value="{{$adminuser->phone}}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password">
            </div>
            <div class="d-flex justify-content-center">
                <button class="back-btn btn btn-dark mr-2">Cancel</button>
                <button class="btn theme" type="submit">Update</button>

            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\UpdateAdminUser','#update') !!}

<script>



</script>
@endsection
{{-- /admin  admin.index

    /admin/create admin.create

    /admin
    --}}
