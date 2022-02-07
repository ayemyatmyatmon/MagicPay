@extends('backend.layouts.app')
@section('title','Wallet')
@section('wallet','mm-active')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-display2 icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>
                <p>Add Amount</p>
            </div>
        </div>

    </div>
</div>

<div class="card">
    @include('backend.layouts.flash')
    <form action="{{url('admin/wallet/add-amount/store')}}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <select name="user_id" class="form-control">
                    <option>---Please Choose----</option>
                    @foreach ($users as $user )
                        <option value="{{$user->id}}">{{$user->name}} ({{$user->phone}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" name="amount" class="form-control">

            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div>
                <button class="back-btn btn btn-dark mr-2">Cancel</button>
                <button class="btn theme" type="submit">Confirm</button>
            </div>

        </div>
    </form>

</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {



        });
</script>
@endsection
