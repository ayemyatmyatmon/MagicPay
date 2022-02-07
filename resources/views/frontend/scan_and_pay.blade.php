@extends('frontend.layouts.app')
@section('title','Scan & Pay')
@section('content')
<div class="container receivedqr d-flex justify-content-center ">
    <div class="col-md-8 " style="padding:0">
        <div class="card text-center">
            <div class="card-body ">
                <div>
                    <img src="{{asset('frontend/img/qrscan.png')}}">

                </div>
                <p>@lang('messages.click-button')</p>
                <!-- Button trigger modal -->
                <button class="btn btn-theme " data-toggle="modal" data-target="#scanModal">@lang('messages.scan')</button>


                <!-- Modal -->
                <div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">@lang('messages.scan-pay')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <video id="scanner" width="100%" height="240px"></video>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
@section('scripts')
<script src="{{asset('frontend/js/qr-scanner.umd.min.js')}}"></script>
<script>
    $(document).ready(function(){
            var videoElem=document.getElementById('scanner');
            const qrScanner = new QrScanner(videoElem,function(result){
                if(result){
                    qrScanner.stop();
                    $('#scanModal').modal('hide');
                    var to_phone=result;
                    window.location.replace(`scan-and-pay-transfer?to_phone=${to_phone}`);
                }
        });

        $('#scanModal').on('show.bs.modal', function (e) {
            qrScanner.start();

        });
        $('#scanModal').on('hidden.bs.modal', function (e) {
            qrScanner.stop();

        });
    })

</script>
@endsection
