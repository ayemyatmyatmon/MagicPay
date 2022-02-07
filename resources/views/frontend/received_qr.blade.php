@extends('frontend.layouts.app')
@section('title','Received Qr')
@section('content')
<div class="container receivedqr d-flex justify-content-center ">
    <div class="col-md-8 " style="padding:0">
        <div class="card">
            <div class="card-body text-center">
                <p class="mb-0">QR scan to pay me.</p>
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($authusers->phone)) !!} ">
                <p class="mb-1"><strong>{{$authusers->name}}</strong></p>
                <p class="mb-1">{{$authusers->phone}}</p>

            </div>
        </div>
    </div>

</div>

@endsection
@section('scripts')
<script>

</script>
@endsection
