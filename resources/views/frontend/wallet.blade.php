@extends('frontend.layouts.app')
@section('title','Wallet')
@section('content')
<div class="d-flex justify-content-center wallet">
    <div class="col-md-8 ">
        <div class="card">
            <div class="card-body">
                <div>
                    <h6>@lang('messages.balance')</h6>
                    <h6>{{number_format($authuser->amount ? $authuser->amount :'-',2)}} MMK</h6>
                </div>
                <div class="mt-3">
                    <h6>@lang('messages.account number')</h6>
                    <h6>{{$authuser->account_number ? $authuser->account_number : '-'}}</h6>
                </div>
                <h5 class="mt-4">{{$user->name}}</h5>
            </div>

        </div>

    </div>

</div>


@endsection
