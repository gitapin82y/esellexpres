@extends('layouts.admin')
 
@section('title', 'List Penjual')
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
                        <div class="col-md-6 text-left col-12">
                            <h4>Seller List</h4>
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
            scrollX: true,
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
                    data: 'list_produk',
                    name: 'list_produk',
                },
            ],

        });

        
        $('body').on('click', '.profitSeller', function () {
            var id = $(this).data('id');
            $.get("{{ route('list-penjual.index') }}" + '/' + id + '/profit', function (data) {
                $('#profit').modal('show');
                $('#id').val(id);
                $('.profitinput').val(data.data.stores.profit);
            });
        });

  


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
                url: "list-penjual/profit",
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
