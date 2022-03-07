<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    {{-- -------_Bootstrap------ --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    {{-- ---------Date Range Picker----- --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    {{-- ---------FontAwesome---- --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css " rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/css/style.css')}}">

    @yield('extra.css')
</head>

<body>

    <div class="content">
        <div class="d-flex header-menu justify-content-center">
            <div class="col-md-8">
                <div class="row py-3">
                    <div class="col-4 text-center">
                        @if(!request()->is('/'))
                        <a href="#" class="back-btn">
                            <i class="fas fa-angle-left"></i>
                        </a>


                        @endif
                    </div>
                    <div class="col-4 text-center">
                        <h4>@yield('title')</h4>

                    </div>
                    <div class="col-2 text-left">
                        <a href="{{url('/notification')}}">
                            <i class="fas fa-bell mt-2"></i>
                            <span
                                class="unreadnoti badge badge-pill badge-danger">@if($unread_noti_count>=1){{$unread_noti_count}}
                                @endif</span>
                        </a>

                    </div>
                    <div class="col-2">
                        <div class="dropdown show " id="language_switcher">
                            {{-- <div class="btn btn-light dropdown-toggle " href="#" role="button"
                                id="dropdownMenuLink" data-toggle="dropdown">
                                Language
                            </div> --}}
                            <button class="btn  dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">

                                @if(app()->getLocale()=='mm')
                                <img src="{{asset('frontend/img/mmflag.png')}}" style='width:23px;'>
                                မြန်မာ
                                @else
                                <img src="{{asset('frontend/img/enflag.png')}}" style='width:23px;'>
                                English
                                @endif
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                <a class="btn dropdown-item" href="#" name="language" data-lang="en">Enlish</a>
                                <a class=" btn dropdown-item" href="#" name="language" data-lang="mm">မြန်မာ</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="content ">
            @yield('content')
        </div>

        <div class="d-flex bottom-menu justify-content-center">
            <div class="col-md-8">
                <a href="{{url('scan-and-pay')}}" class="d-flex justify-content-center align-items-center qrcode ">
                    <div class="inside">
                        <i class="fas fa-qrcode"></i>

                    </div>
                </a>
                <div class="row">

                    <div class="col-3 text-center">
                        <a href="{{route('home')}}">
                            <i class="fas fa-home mt-3"></i>
                            <p>@lang('messages.home')</p>
                        </a>

                    </div>
                    <div class="col-3 text-center">
                        <a href="{{route('wallet')}}">
                            <i class="fas fa-wallet mt-3"></i>
                            <p>@lang('messages.wallet')</p>
                        </a>

                    </div>
                    <div class="col-3 text-center">
                        <a href="{{route('transaction')}}">
                            <i class="fas fa-exchange-alt mt-3"></i>
                            <p>@lang('messages.transaction')</p>
                        </a>

                    </div>
                    <div class="col-3 text-center">
                        <a href="{{route('profile')}}">
                            <i class="fas fa-user mt-3"></i>
                            <p>@lang('messages.profile')</p>
                        </a>

                    </div>
                </div>
            </div>
        </div>

    </div>




    {{-- -------_Bootstrap------ --}}
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    {{-- ------------Sweet Alert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- -------daterange picker --}}

    <script src="{{asset('frontend/js/moment.min.js')}}"></script>
    <script src="{{asset('frontend/js/daterangepicker.min.js')}}"></script>

    <script>
        $(document).ready(function(){
            let token=document.head.querySelector('meta[name="csrf-token"]');
            if(token){
                $.ajaxSetup({
                    headers : {
                        'X-CSRF_TOKEN' : token.content,
                        'Content-Type' :'application/json',
                        'Accept'       :'application/json',

                    }
                });
            }


            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })

                @if(session('update'))
                    Toast.fire({
                    icon: 'success',
                    title: "{{session('update')}}",
                    })
                @endif

                $('.back-btn').on('click',function(e){
                e.preventDefault();
                window.history.back();
                return false;
            })
                $(document).on('click','#language_switcher a',function(){
                    var language=$(this).attr('data-lang');


                    $.ajax({
                        url:`/language-switcher?language=${language}`,
                        type:'GET',
                        success:function(res){
                            if(res.result==1){
                                window.location.reload();

                            }
                        }
                    })
                })

            })


    </script>
    @yield('scripts')

</body>

</html>
