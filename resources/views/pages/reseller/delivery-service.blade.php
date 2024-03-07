@extends('layouts.admin')
 
@section('title', 'Delivery List')
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
                        <div class="row col-12">
                            <div class="col-md-6 text-left col-12 align-self-center">
                                    <h4 class="mb-0">Current Shipping Costs:</h4>
                                    <span class="text-main">${{ number_format($fee->fee, 2) }}</span>
                                    <a class="mx-1 text-info" style="cursor: pointer" onclick="openEditModal()">
                                    <label class="fa fa-edit"></label> Edit</a>
                            </div>
                            <div class="col-md-6 text-right col-12">
                                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalDelivery">Add Delivery</button>
                            </div>
                        </div>
                       
                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableListDelivery" class="table w-100">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
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

<div id="editShippingFeeModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
      <div class="modal-content">
        <div class="modal-header bg-gradient-info">
          <h4 class="modal-title d-inline">Form Edit Shipping Costs</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editShippingFeeForm">
                @csrf
                @method('PATCH')
                <label for="editFeeInput" class="form-control-label">Shipping Costs:<span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                          </div>
                        <input type="text" id="editFeeInput" name="fee" placeholder="0.00" oninput="handleInput(this)"
                            class="form-control">
                    </div>
                <button type="button" class="btn btn-main" onclick="updateShippingFee()">Save Changes</button>
            </form>
        </div>
        </div>
  
    </div>
  </div>

<div id="modalDelivery" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
      <div class="modal-content">
        <div class="modal-header bg-gradient-info">
          <h4 class="modal-title d-inline">Form Delivery</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
         <form method="POST"  id="form-delivery">
                @csrf
                <input type="hidden" class="form-control form-control-sm inputtext" id="id" name="id">
            <div class="form-group">
                <label for="account_number">
                    Name Delivery<span style="color:red;">*</span>
                </label>
                <input type="text" class="form-control form-control-sm inputtext" id="nameDelivery" name="name" required>
            </div>

            <button class="btn btn-main" id="simpan" type="submit" onclick="this.disabled=true;this.form.submit();">Save</button>

         </form>
        </div>
        </div>
  
    </div>
  </div>
  




@endsection

@push('after-script')
<script>
    function handleInput(inputElement) {
          let value = inputElement.value;
  
          // Menghapus karakter selain angka dan titik
          value = value.replace(/[^\d.]/g, '');
  
          // Mencegah lebih dari satu titik desimal
          let parts = value.split('.');
          if (parts.length > 2) {
              value = parts[0] + '.' + parts.slice(1).join('');
          }
  
          // Memastikan value bukan NaN
          if (!isNaN(value)) {
              // Memberikan format dolar dengan dua digit desimal
              inputElement.value = value;
          } else {
              // Jika input tidak valid, set nilai ke kosong
              inputElement.value = '';
          }
      }
  </script>
  
<script>
    jQuery(document).ready(function ($) {

        const editFeeInput = document.getElementById('editFeeInput');
    const editShippingFeeForm = document.getElementById('editShippingFeeForm');

    window.openEditModal = function() {
        editFeeInput.value = parseFloat("{{ $fee->fee }}").toFixed(2);
            $('#editShippingFeeModal').modal('show');
        };

        window.updateShippingFee = function() {
            var formData = new FormData(editShippingFeeForm);
    
            $.ajax({
                url: "{{ route('shipping-fee.update') }}",
                type: "POST", // Change to POST as PATCH might not be supported by all browsers
                data: formData,
                processData: false,
                contentType: false,
                cache: false, // Ensure no caching
                success: function(response) {
                    $('#editShippingFeeModal').modal('hide');
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        };

        var table = $('#tableListDelivery').DataTable({
            processing: true,
            serverside: true,
            // scrollX: true,
            // "order": [
            //     [0, "asc"]
            // ],
            ajax: {
                url: '{{route("getListDelivery")}}',
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
                    data: 'created_at',
                    name: 'created_at'
                },{
                    data: 'action',
                    name: 'action',
                },
            ],
            "columnDefs": [{
                "className": "dt-center",
                "targets": "_all"
            }],

        });
        
        $('body').on('click', '.updateItems', function () {
 
            var id = $(this).data('id');
            $.get("{{ route('delivery-service.index' ) }}"+'/'+id, function (data) {
                $('#modalDelivery').modal('show');
                $('#id').val(id);
                $('#nameDelivery').val(data.data.name);
            });
        });

        $('body').on('click', '.deleteItems', function (e) {
            e.preventDefault();
       
            var id = $(this).data('id');
            $.ajax({
                headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
                url: "{{ route('delivery-service.index' ) }}" + '/' + id,
                method: 'DELETE',

                success: function (data) {
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
            var data = $('#form-delivery');
            var formData = data.serialize();

            $.ajax({
                type: "POST",
                url: "{{ route('delivery-service.store') }}",
                data: formData,
        
                success: function (data) {
                    if (data.errors) {
                        console.log('error');
                    }
                    if (data.success) {
                        $('#modalDelivery').modal('hide');
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
     
            $('#modalDelivery').on('hidden.bs.modal', function () {
                // Mengosongkan nilai formulir saat modal ditutup
                $('#id').val('');
                $('#form-delivery')[0].reset();
            });
    });

    // $('#modal').modal('show');
      // aksi ajax jika tombol edit di klik
    

</script>

{{-- @include('sweetalert::alert') --}}
@endpush
{{-- end script khusus pada pages daftar anggota --}}
