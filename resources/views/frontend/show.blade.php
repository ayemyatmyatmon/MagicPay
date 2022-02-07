@extends('frontend.layouts.app')
@section('title','Notification Detail')
@section('content')
<div class="container notification">
    <div style="margin-top:100px">

            <div class="card mb-2">
                <div class="card-body text-center">
                    <div>
                        <img src="{{asset('frontend/img/notification.png')}}" style="width:220px;">

                    </div>
                        <h6><i class="fas fa-bell "></i>
                            {{$notification->data['title']}}</h6>
                        <p>{{$notification->data['message']}}</p>
                        <p class="text-muted">
                            <small>{{Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i:s A')}}</small>
                        </p>

                        <a href="{{$notification->data['web_link']}}" class="btn btn-theme"> Continue</a>
                </div>
            </div>


    </div>





</div>

@endsection
