@extends('layouts.admin')
 
@section('title', 'Request Penjual')
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
                        <div class="row">

                        <div class="col-md-4 text-left col-12">
                            <h4>Shop status request from seller</h4>
                        </div>
                    </div>

                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableRequestPenjual" class="table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Email</th>
                                        <th>Name Store</th>
                                        <th>Status</th>
                                        <th>Request</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- data table ajax--}}
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
        var table = $('#tableRequestPenjual').DataTable({
            processing: true,
            serverside: true,
            scrollX: true,
            "order": [
                [0, "asc"]
            ],
            ajax: {
                url: '{{route("getRequestPenjual")}}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'stores.users.email',
                    name: 'stores.users.email',
                    render: function(data, type, full, meta) {
                        // Check if 'users' relationship is loaded
                        if (full.stores.users) {
                            return full.stores.users.email;
                        } else {
                            return ''; // Or handle it accordingly
                        }
                    },
                },{
                    data: 'stores.name',
                    name: 'stores.name'
                },{
                    data: 'stores.is_active',
                    name: 'stores.is_active',
                    render: function(data) {
                        if(data == "ON"){
                            return '<span class="text-success">'+data+'</span>'
                        }else{
                            return '<span class="text-danger">'+data+'</span>'
                        }
                    }
                },{
                    data: 'request',
                    name: 'request',
                    render: function(data) {
                        if(data == "ON"){
                            return '<span class="text-success">'+data+'</span>'
                        }else{
                            return '<span class="text-danger">'+data+'</span>'
                        }
                    }
                },{
                    data: 'created_at',
                    name: 'created_at',
                },{
                    data: 'action',
                    name: 'action',
                },
            ],

        });


        $('body').on('click', '.statusStore', function () {
            var id = $(this).data('id');
            var status = $(this).data('status');
            var reqid = $(this).data('reqid');
            $.get('request-penjual/status?is_active='+status+'&id='+id+'&reqid='+reqid, function (data) {
                        table.ajax.reload();

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        Toast.fire({
                            icon: 'success',
                            title: data.success 
                        });
            });
        });
    
    });

    // $('#modal').modal('show');
      // aksi ajax jika tombol edit di klik
    

</script>

{{-- @include('sweetalert::alert') --}}
@endpush
{{-- end script khusus pada pages daftar anggota --}}
