@extends('frontend.layouts.app')
@section('title', __('messages.profile'))
@section('content')
<div class="container">
    <div class="d-flex justify-content-center ">
        <div class="profile mt-4">
            <img src="https://ui-avatars.com/api/?background=73F340&name={{Auth::user()->name}}">
        </div>

    </div>
    <div class="d-flex justify-content-center mt-3 profile">
        <div class="col-md-8 col-sm-12" style="padding:0;">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <span>@lang('messages.name')</span>
                    <span>{{Auth::user()->name}}</span>
                </div>
                <hr class="m-0">
                <div class="card-body d-flex justify-content-between">
                    <span>@lang('messages.email')</span>
                    <span>{{Auth::user()->email}}</span>
                </div>
                <hr class="m-0">
                <div class="card-body d-flex justify-content-between">
                    <span>@lang('messages.phone')</span>
                    <span>{{Auth::user()->phone}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-3 profile">
        <div class="col-md-8 col-sm-12 " style="padding:0;">
            <div class="card">
                <a href="{{route('updatepassword')}}" class="card-body d-flex justify-content-between">
                    <span>@lang('messages.updatepassword')</span>
                    <span><i class="fas fa-angle-right"></i></span>
                </a>
                <hr class="m-0">
                <a href="#" class="Logout card-body d-flex justify-content-between ">
                    <span>@lang('messages.logout')</span>
                    <span><i class="fas fa-angle-right"></i></span>

                </a>

            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $(document).on('click','.Logout',function(e){
            e.preventDefault();


            Swal.fire({
                title:"@lang('messages.logout-warning-message')",
                showCancelButton: true,
                confirmButtonText: `@lang('messages.confirm')`,
                cancelButtonText: `@lang('messages.cancel')`,
                reverseButtons  :true
                })
                .
                then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{route('logout')}}",
                        type : 'GET',
                        success:function(){
                        window.location.replace('{{route("login")}}');

                        }

                    })

                    }
                })
        })
    })
</script>
@endsection
