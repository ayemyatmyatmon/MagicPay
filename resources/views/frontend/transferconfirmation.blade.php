@extends('frontend.layouts.app')
@section('title','Transfer Confirmation')
@section('content')
<div class=" d-flex justify-content-center">
    <div class="col-md-8">
        <div class="card transferconfirmation p-3">

            <p class="mb-1">From</p>
            <p class="text-muted mb-1">{{$fromuser->name}}</p>
            <p>{{$fromuser->phone}}</p>

            <form method="POST" action="{{url('/transfer/complete')}}" id="form">
                @include('frontend.layouts.flash')

                @csrf

                <input name="to_phone" class="from-control" type="hidden" value="{{$to_account->phone}}">
                <input name="amount" class="from-control" type="hidden" value="{{$amount}}" >
                <input name="description" class="from-control" type="hidden" value="{{$description}}">

                <div>
                    <label>To</label>
                    <p >{{$to_account->name}}</p>
                    <p class="text-muted">{{$to_account->phone}}</p>
                </div>

                <div >
                    <label>@lang('messages.amount')(MMK)</label>
                    <p class="text-muted">{{number_format($amount,2)}}</p>
                </div>

                <div >
                    <label>@lang('messages.description')</label>
                    <p class="text-muted">{{$description}}</p>
                </div>

                <button type="submit" class="btn btn-theme btn-block my-2 py-2 check-password"> @lang('messages.confirm')</button>
            </form>

        </div>

    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('.check-password').on('click',function(e){
            e.preventDefault();
            Swal.fire({
            title: 'Please Enter your password',
            icon: 'info',
            html:'<input type="password" name="password" class="text-center form-control password">',
            showCloseButton :true,
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            reverseButtons: true
            })
            .then((result) => {
                if (result.isConfirmed) {
                    var password=$('.password').val();
                    $.ajax({
                        url : '/check-password?password=' + password,
                        type : 'GET',
                        success:function(res){
                            if(res.status=="success"){
                                $('#form').submit();
                            }else{
                                Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: res.message,
                                })
                            };

                        }
            });
                }

            })
        })
    })
</script>
@endsection
