@extends('frontend.layouts.app')
@section('title','Notification')
@section('content')
<div class="container notification">
    <div style="margin-top:100px">
        @foreach ($notifications as $notification )

            <div class="card mb-2">
                <div class="card-body">
                    <a href="{{url('notification/'.$notification->id)}}">
                        <h6><i class="fas fa-bell @if(is_null($notification->read_at))text-danger @endif"></i>
                            {{$notification->data['title']}}</h6>
                        <p>{{$notification->data['message']}}</p>
                        <p class="text-muted">
                            <small>{{Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i:s A')}}</small>
                        </p>
                    </a>


                </div>
            </div>

        @endforeach


    </div>





</div>

@endsection
