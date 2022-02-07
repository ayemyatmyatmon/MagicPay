@extends('frontend.layouts.app')
@section('title','Transaction Details')
@section('content')
<div class="d-flex justify-content-center transactiondetails">
    <div class="col-md-8 ">
        <div class="card mt-3">

            <div class="card-body">

                <div class="text-center mb-3">
                    <img src="{{asset('frontend/img/transfer.png')}} " class="text-center">

                </div>
                @if(session('success'))
                <div class="alert alert-success text-center" role="alert">
                    {{session('success')}}
                </div>
                @endif
                @if ($transaction->type==1)
                <p class="text-center text-success mb-1">{{number_format($transaction->amount)}}MMK</p>
                @elseif($transaction->type==2)
                <p class="text-center text-danger mb-1">{{number_format($transaction->amount)}}MMK</p>
                @endif

                <div class="d-flex justify-content-between mt-3">
                    <p class="mb-0 text-muted">@lang('messages.trx_id')</p>
                    <p class="mb-0"> {{$transaction->trx_id}}</p>
                </div>
                <hr>

                <div class="d-flex justify-content-between mt-3">
                    <p class="mb-0 text-muted">@lang('messages.reference-number')</p>
                    <p class="mb-0"> {{$transaction->ref_no}}</p>
                </div>
                <hr>

                <div class="d-flex justify-content-between mt-3">

                    @if ($transaction->type==1)
                    <p class="text-center  mb-1">@lang('messages.type')</p>
                    <p class="text-center badge badge-pill badge-success mb-1">@lang('messages.income')</p>

                    @elseif($transaction->type==2)
                    <p class="text-center  text-muted mb-1">@lang('messages.type')</p>
                    <p class="text-center badge badge-pill badge-danger mb-1">@lang('messages.expense')</p>

                    @endif
                </div>
                <hr>

                <div class="d-flex justify-content-between mt-3">
                    <p class="mb-0 text-muted">@lang('messages.amount')</p>
                    <p class="mb-0"> {{number_format($transaction->amount)}}MMK</p>
                </div>
                <hr>

                <div class="d-flex justify-content-between mt-3">
                    <p class="mb-0 text-muted">@lang('messages.date-time')</p>
                    <p class="mb-0"> {{$transaction->created_at}}</p>
                </div>
                <hr>

                <div class="d-flex justify-content-between mt-3">
                    @if ($transaction->type==1)
                    <p class="text-center  mb-1">From</p>
                    <p class="text-center  mb-1">{{$transaction->source ? $transaction->source->name :'-'}}
                    </p>

                    @elseif($transaction->type==2)
                    <p class="text-center  text-muted mb-1">To</p>
                    <p class="text-center  mb-1">{{$transaction->source ? $transaction->source->name :'-'}}</p>


                    @endif
                </div>
                <hr>

                <div class="d-flex justify-content-between mt-3">
                    <p class="mb-0 text-muted">@lang('messages.description')</p>
                    <p class="mb-0"> {{$transaction->description}}</p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>

</script>
@endsection
