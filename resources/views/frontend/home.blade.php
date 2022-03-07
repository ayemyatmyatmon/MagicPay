@extends('frontend.layouts.app')
@section('title', __('messages.home'))
@section('content')
<div class="container">
    <div class="home ">
        <div class="col-12">
            <div class="profile text-center ">
                <img src="https://ui-avatars.com/api/?background=73F340&name={{Auth::user()->name}}">
                <p class="text-center">{{$authuser->name ? $authuser->name:'-'}}</p>
                <p class="text-center text-muted">{{number_format($users->amount ? $users->amount : 0 ,2)}} MMK</p>
            </div>

        </div>
        <div class="d-flex justify-content-center">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-6 p-1">
                        <div class="card shortcut-box ">
                            <a href="{{url('/scan-and-pay')}}" style="text-decoration: none;color:#333;">
                                <div class="card-body p-2">
                                    <img src="{{asset('frontend/img/scan.png')}}">
                                    <span>@lang('messages.scan')</span>
                                </div>
                            </a>

                        </div>
                    </div>
                    <div class="col-6 p-1">
                        <div class="card shortcut-box ">
                            <a href="{{url('/received-qr')}}" style="text-decoration: none;color:#333;">
                                <div class="card-body p-2 ">
                                    <img src="{{asset('frontend/img/qr-code.png')}}">
                                    <span>@lang('messages.received-qr')</span>
                                </div>
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3 function-box">
            <div class="col-md-8 col-sm-12 p-0">
                <div class="card ">
                    <a href="{{url('/transfer')}}" class="card-body d-flex justify-content-between">
                        <span><img src="{{asset('frontend/img/money-transfer.png')}}">@lang('messages.transfer')</span>
                        <span><i class="fas fa-angle-right"></i></span>
                    </a>
                    <hr class="m-0">
                    <a href="{{url('/wallet')}}" class="Logout card-body d-flex justify-content-between ">
                        <span><img src="{{asset('frontend/img/wallet.png')}}">@lang('messages.wallet')</span>
                        <span><i class="fas fa-angle-right"></i></span>

                    </a>
                    <hr class="m-0">
                    <a href="{{url('/transaction')}}" class="Logout card-body d-flex justify-content-between ">
                        <span><img src="{{asset('frontend/img/transaction.png')}}">@lang('messages.transaction')</span>
                        <span><i class="fas fa-angle-right"></i></span>

                    </a>

                </div>
            </div>
        </div>

    </div>


</div>

@endsection
