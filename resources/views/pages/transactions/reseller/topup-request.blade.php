@extends('layouts.admin')
 
@section('title', 'Topup Request')
 @push('after-style')
 <style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }

    .dtfc-fixed-right{
        background-color: rgba(255, 255, 255, 0.768);
    }
</style>    

 @endpush
@section('content')

<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white py-4 mb-4">
                        <div class="row col-12">
                            <div class="col-md-6 text-left col-12 align-self-center">
                                <h4>Top Up Request</h4>
                            </div>
                            <?php
                            use App\Models\BadgeSidebar;
                            $withdraw = BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'withdraw')->first();
                            $withdrawTotal = $withdraw ? $withdraw->total : 0;
                            ?>
                            <div class="col-md-6 text-left col-12 justify-content-end row">
                                <a href="{{route('topup-request.index')}}" class="btn mr-2 {{Request::is('topup-request') ? 'btn-main' : 'btn-border' }}">Top Up Request</a>
                                <a href="{{route('withdraw-request.index')}}" class="btn {{Request::is('withdraw-request') ? 'btn-main' : 'btn-border' }}">Withraw Request </a>
                                {!!$withdrawTotal > 0 ? '<div class="badge" style="top: -10px;margin-left:-10px;"><span>'.$withdrawTotal.'</span></div>' : ''!!}
                            </div>
                        </div>
                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableBalanceRequest" class="table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Category</th>
                                        <th>Transaction</th>
                                        <th>Total</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@endsection

@push('after-script')
<script>
       jQuery(document).ready(function ($) {
        var table = $('#tableBalanceRequest').DataTable({
            processing: true,
            serverside: true,
            // fixedColumns: {
            //     right: 1,
            //     left: 0,
            // },
            scrollX: true,
            "order": [
                [0, "asc"]
            ],
            ajax: {
                url: '{{route("getTopupRequest")}}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'users.name',
                    name: 'users.name',
                },
                {
                    data: 'users.email',
                    name: 'users.email'
                },
                {
                    data: 'users.phone',
                    name: 'users.phone'
                },
                {
                    data: 'users.role',
                    name: 'users.role',
                    render: function(data) {
                        if(data == 2){
                            return 'seller';
                        }else{
                            return 'buyer';
                        }
                    }
                },{
                    data: 'submission',
                    name: 'submission',
                },{
                    data: 'proof',
                    name: 'proof',
                    render: function(data) {
                        return '<a href="'+data+'" data-lightbox="roadtrip"><img src="'+data+'" width="90px"></a>'
                    }

                },{
                    data: 'total',
                    name: 'total',
                    render: function(data) {
                        return '$'+data
                    }
                },{
                    data: 'message',
                    name: 'message',
                },{
                    data: 'status',
                    name: 'status',
                    render : function(data){
                        if(data == 'Pending'){
                            return '<span class="text-warning">Pending</span>';
                        }else if(data == 'Success'){
                            return '<span class="text-success">Success</span>';
                        }else{
                            return '<span class="text-danger">Failure</span>';
                        }
                    }
                },{
                    data: 'action',
                    name: 'action',
                },
            ],

        });
        // end data table ajax

        // aksi ajax jika tombol edit di klik
        $('body').on('click', '.accRequest', function () {
            var id = $(this).data('id');
            $.get("{{ route('topup-request.index') }}" + '/' + id + '/acc', function (data) {
                const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            Toast.fire({
                icon: 'success',
                title: 'Successfully acc, the user balance increases'
            });

            table.ajax.reload();


            });
        });



     // aksi ajax jika tombol edit di klik
     $('body').on('click', '.rejectRequest', function () {
            var id = $(this).data('id');
            $.get("{{ route('topup-request.index') }}" + '/' + id + '/reject', function (data) {
                const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            Toast.fire({
                icon: 'success',
                title: 'Successful in reject'
            });

            $('#tableBalanceRequest').DataTable().ajax.reload();

            });
        });


    });

</script>
@endpush
{{-- end script khusus pada pages daftar anggota --}}
