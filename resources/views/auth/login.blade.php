@extends('frontend.layouts.app-plane')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="height:100vh;">
        <div class="col-md-6">
            <div class="card auth-form ">
                <div class="card-body">
                    <h4 class="text-center">Login Form</h4>
                    <p class="text-muted text-center">Please Enter the your data</p>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf


                        <div class="form-group">
                            <label>Phone Number</label>
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Passward</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="form-control btn-theme mt-5">Login</button>
                        <div class="d-flex justify-content-between">
                                <a href="{{route('register')}}">Create Register</a>
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
