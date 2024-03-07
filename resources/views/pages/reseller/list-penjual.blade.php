@extends('layouts.admin')
 
@section('title', 'List Seller')
 @push('after-style')
 
 <style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
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
                        <div class="col-md-4 text-left align-self-center col-12">
                            <h4>Seller List</h4>
                        </div>
                        <div class="col-md-8 text-right col-12">
                            <h5 class="d-inline mr-2">Filter Status</h5>
                            <button class="btn btn-warning btn-sm mr-2" id="showAllStatusBtn">All</button>
                            <button class="btn btn-danger btn-sm mr-2" id="statusOffBtn">Status Off</button>
                            <button class="btn btn-success btn-sm" id="statusOnBtn">Status On</button>
                        </div>     
                    </div>

                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableListPenjual" class="table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Card</th>
                                        <th>Name Store</th>
                                        <th>Logo Store</th>
                                        <th>Total Sales</th>
                                        <th>Percent Profit</th>
                                        <th>Address</th>
                                        <th>Status</th>
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

<!-- Modal -->
<div id="profit" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-gradient-info">
          <h4 class="modal-title d-inline">Form Profit</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
         <form method="POST"  id="form-persentase">
                @csrf
            <input type="hidden" class="form-control form-control-sm inputtext" id="id" name="id">
            <div class="form-group">
                <label for="">
                    Persentase Profit <span style="color:red;">*</span>
                </label>
                <input type="text" class="form-control form-control-sm inputtext profitinput" name="profit">
            </div>
            <button class="btn btn-success" id="simpan" type="submit">Set Profit</button>

         </form>
        </div>
        </div>
  
    </div>
  </div>
  




@endsection

@push('after-script')
<script>
    jQuery(document).ready(function ($) {
        var table = $('#tableListPenjual').DataTable({
            processing: true,
            serverside: true,
            // scrollX: true,
            "order": [
                [0, "asc"]
            ],
            ajax: {
                url: '{{route("getListPenjual")}}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'card',
                    name: 'card'
                },{
                    data: 'stores.name',
                    name: 'stores.name'
                },
                {
                    data: 'stores.logo',
                    name: 'stores.logo',
                    render: function(data) {
                        return '<a href="'+data+'" data-lightbox="roadtrip"><img src="'+data+'" width="90px"></a>'
                    }

                },{
                    data:'total_sales',
                    name:'total_sales',  
                },{
                    data: 'stores.profit',
                    name: 'stores.profit',
                    render: function(data) {
                        return data+'%'
                    }
                },{
                    data: 'address',
                    name: 'address',
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
                    data: 'list_produk',
                    name: 'list_produk',
                },
            ],

        });

        
        $('body').on('click', '.profitSeller', function () {
            var id = $(this).data('id');
            $.get("{{ route('seller-list.index') }}" + '/' + id + '/profit', function (data) {
                $('#profit').modal('show');
                $('#id').val(id);
                $('.profitinput').val(data.data.stores.profit);
            });
        });

        $('body').on('click', '.statusStore', function () {
            var id = $(this).data('id');
            var status = $(this).data('status');
            console.log(status);
            $.get('seller-list/status?is_active='+status+'&id='+id, function (data) {
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

  
 // Tambahkan event listener untuk tombol "Status Off"
        $('#statusOffBtn').on('click', function () {
            updateStatusFilter('OFF');
        });

        // Tambahkan event listener untuk tombol "Status On"
        $('#statusOnBtn').on('click', function () {
            updateStatusFilter('ON');
        });

          // Tambahkan event listener untuk tombol "Show All Status"
          $('#showAllStatusBtn').on('click', function () {
            showAllStatus();
        });


        function updateStatusFilter(status) {
            // Set filter pada kolom "Status" sesuai dengan status yang diklik
              table.column('stores.is_active:name').search(status).draw();

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            Toast.fire({
                icon: 'success',
                title: 'Filtered by Status ' + status
            });
        }

        function showAllStatus() {
            // Hapus filter pada kolom "Status"
            table.column('stores.is_active:name').search('').draw();

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            Toast.fire({
                icon: 'success',
                title: 'Show All Status'
            });
        }

        // $('#simpan').click(function (e) {
            $('body').on('click', '#simpan', function (e) {

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            var data = $('#form-persentase');
            var formData = data.serialize();

            $.ajax({
                type: "POST",
                url: "seller-list/profit",
                data: formData,
        
                success: function (data) {
                    if (data.errors) {
                        console.log('error');
                    }
                    if (data.success) {

                        $('#profit').modal('hide');
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

                    }
                }
            });

            });
     

    });

    // $('#modal').modal('show');
      // aksi ajax jika tombol edit di klik
    

</script>

{{-- @include('sweetalert::alert') --}}
@endpush
{{-- end script khusus pada pages daftar anggota --}}
