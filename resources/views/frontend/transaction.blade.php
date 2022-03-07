@extends('frontend.layouts.app')
@section('title', __('messages.transaction'))
@section('content')
<div class="container">
    <div class="d-flex justify-content-center ">
        <div class="col-md-8 transaction " style="padding:0;">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="row">

                        <div class="col-6" style="padding:0px 7px;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text p-1" >@lang('messages.date')</label>
                                </div>
                                <input type="text" class="date form-control " value="{{request()->date }}" placeholder="@lang('messages.all')" >
                            </div>
                        </div>

                        <div class="col-6" style="padding:0px 7px">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text p-1">@lang('messages.type')</label>
                                </div>
                                <select class="custom-select type">
                                    <option value="">@lang('messages.all')</option>
                                    <option value="1" @if(request()->type==1) selected @endif >@lang('messages.income')</option>
                                    <option value="2" @if(request()->type==2) selected @endif>@lang('messages.expense')</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            @foreach($transactions as $transaction)
            <a href="{{url('transaction/'.$transaction->trx_id)}}">

                <div class="card mb-2">
                    <div class="card-body">
                        <span class="d-flex justify-content-between">
                            <h6>{{$transaction->trx_id}}</h6>
                            <p class="@if ($transaction->type==1)text-success

                                @elseif($transaction->type==2)text-danger

                                @endif">{{$transaction->amount}}<small>MMK</small></p>
                        </span>

                        @if ($transaction->type==1)
                        <p class="mb-1 "> From</p>

                        @elseif($transaction->type==2)
                        <p class="mb-1">To</p>

                        @endif
                        <p class="text-muted mb-1 ">{{$transaction->source ? $transaction->source->name :'-'}}</p>
                        <p class="text-muted mb-1">{{$transaction->created_at}}</p>
                    </div>
                </div>
            </a>

            @endforeach

            {{$transactions->links()}}

        </div>

    </div>
</div>


@endsection
@section('scripts')
<script>

        $('.date').daterangepicker({
                "singleDatePicker": true,
                "autoApply": false,
                "autoUpdateInput":false,
                "locale": {
                    "format": "YYYY-MM-DD",
                },

            });

            $('.type').change(function(){
                var date=$('.date').val();
                var type=$('.type').val();
                history.pushState(null,'',`?date=${date}&type=${type}`);
                window.location.reload();
            });

            $('.date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));

                var date=$('.date').val();
                var type=$('.type').val();
                history.pushState(null,'',`?date=${date}&type=${type}`);
                window.location.reload();
                });

            $('.date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');

                var date=$('.date').val();
                var type=$('.type').val();
                history.pushState(null,'',`?date=${date}&type=${type}`);
                window.location.reload();
            });
</script>
@endsection
