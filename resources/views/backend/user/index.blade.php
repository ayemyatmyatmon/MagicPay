@extends('backend.layouts.app')
@section('title','Users Page')
@section('user','mm-active')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-display2 icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>@yield('title')

            </div>
        </div>

    </div>
</div>
<a href="{{route('admin.user.create')}}" class="btn theme mb-3"  > <i class="fas fa-plus-circle"> Create User</i></a>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered Datatable">
            <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created_at</th>
                <th>Updated_at</th>
                <th>ip</th>
                <th>User Agent</th>
                <th>Login At</th>
                <th>Action</th>
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
        "ajax": "/admin/user/datatable/ssd",
        "columns" :[
            {
                data:'name',
                name:'name'
            },
            {
                data:'email',
                name:'email'
            },
            {
                data:'phone',
                name:'phone'
            },
            {
                data:'created_at',
                name:'created_at'
            },
            {
                data:'updated_at',
                name:'updated_at'
            },

            {
                data:'ip',
                name:'ip'
            },

            {
                data:'user_agent',
                name:'user_agent'
            },
            {
                data:'login_at',
                name:'login_at'
            },
            {
                data:'action',
                name:'action'
            }

        ],

        "columnDefs": [{
        "targets": 0,
        "sortable": false
        }],

        "order": [[ 4, "desc" ]]

    });

        $(document).on('click','.delete',function(e){
            e.preventDefault();

           var id=$(this).data('id');

            Swal.fire({
                title: 'Are you sure,do you want to delete',
                showCancelButton: true,
                confirmButtonText: `Confirm`,

                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : '/admin/user/' + id,
                        type : 'DELETE',
                        success:function(){
                            table.ajax.reload();
                        }

                    })

                    }
                })
        })
});
</script>
@endsection
