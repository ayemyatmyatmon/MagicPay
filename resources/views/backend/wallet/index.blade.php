@extends('backend.layouts.app')
@section('title','Add Amount')
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
                <p>Wallet</p>
            </div>
        </div>

    </div>
</div>
<div class="mb-3">
    <a href="{{url('admin/wallet/add-amount')}}" class="btn btn-success"><i class="fas fa-plus-circle"></i> Add Amount</a>
    <a href="{{url('admin/wallet/reduced-amount')}}" class="btn btn-danger"><i class="fas fa-minus-circle"></i> Reduced Amount</a>

</div>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered Datatable">
            <thead>
                <th>Account Number</th>
                <th>Account Person</th>
                <th>Amount</th>
                <th>Created_at</th>
                <th>Updated_at</th>

            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
    var table= $('.Datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "/admin/wallet/datatable/ssd",
        "columns" :[
            {
                data:'account_number',
                name:'account_number'
            },
            {
                data:'account_person',
                name:'account_person'

            },
            {
                data:'amount',
                name:'amount'
            },
            {
                data:'created_at',
                name:'created_at'
            },
            {
                data:'updated_at',
                name:'updated_at'
            },



        ],

        "columnDefs": [{
        "targets": 0,
        "sortable": false
        }],

        "order": [[ 4, "desc" ]]

    });


});
</script>
@endsection
