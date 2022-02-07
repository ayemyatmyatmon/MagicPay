@extends('frontend.layouts.app')
@section('title','Sacn & Pay Transfer')
@section('content')
<div class=" d-flex justify-content-center my-5">
    <div class="col-md-8">
        <div class="card transfer p-3">
            <p class="mb-1">From</p>
            <p class="text-muted mb-1">{{$from_account->name}}</p>
            <p>{{$from_account->phone}}</p>

            <form action="{{url('/scan-and-pay-transferconfirm')}}" method="GET" id='transfer_form'>
                <input type="hidden" class="hidden_form" name="hidden_form" value="">
                <input type="hidden" class="to_phone" name="to_phone" value="{{request()->to_phone}}">

                <div>
                    <p class="mb-1">To</p>
                    <p class="text-muted mb-1">{{$to_account->name}}</p>
                    <p>{{$to_account->phone}}</p>
                </div>

                <div class="form-group">
                    <label>Amount(MMK)</label>
                    <input type="text" name="amount" class="form-control" value="{{old('amount')}}">
                    @error('amount')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description">{{old('description')}}</textarea>
                </div>
                <button type="submit" class="btn btn-theme btn-block my-2 py-2 submit-btn"> Continue</button>

            </form>
        </div>

    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('.check-btn').on('click',function(){
            var phone=$('.to_phone').val();
            $.ajax({
                        url : '/to-account-verify?phone=' + phone,
                        type : 'GET',
                        success:function(res){
                            if(res.status=="success"){
                                $(".account-info").text('('+res.data['name']+')');
                            }else{
                                $(".account-info").text('');

                            };

                        }
            });
        });

        $('.submit-btn').on('click',function(e){
            e.preventDefault();
                var to_phone=$('.to_phone').val();
                var amount=$('.amount').val();
                var description=$('.amount').val();

            $.ajax({
                url :`/transfer-hash?to_phone=${to_phone}&amount=${amount}&description=${description}`,
                type :'GET',
                success:function(res){
                    console.log(res);
                    if(res.status=='success'){
                        $('.hidden_form').val(res.data);
                        $('#transfer_form').submit();
                    }
                }
            })
        })
    })
</script>
@endsection
