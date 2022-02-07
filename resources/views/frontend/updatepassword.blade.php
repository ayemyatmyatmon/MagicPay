@extends('frontend.layouts.app')
@section('title','Update Password')
@section('content')
<div class="container">
    <div class="d-flex justify-content-center mt-5 updatepassword  ">
        <div class="col-md-8 col-sm-12">
            <div class="card mt-5">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="{{asset('frontend/img/password.png')}}">

                    </div>
                    <form action="{{route('updatepassword-store')}}" method="POST" id="update">
                        @csrf
                        <div class="form-group">
                            <label>@lang('messages.old-password')</label>
                            <input type="password" class="form-control @error('old_password') is-invalid  @enderror" name="old_password" value="{{old('old_password')}}">
                            @error('old_password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>@lang('messages.new-password')</label>
                            <input type="password" class="form-control @error('new_password') is-invalid  @enderror" name="new_password" value="{{old('new_password')}}" >
                            @error('new_password')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-theme btn-block my-2 py-2">@lang('messages.updatepassword')</button>
                    </form>

                </div>

            </div>
        </div>

    </div>


</div>

@endsection
@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\UpdatePassword','#update') !!}

@endsection
