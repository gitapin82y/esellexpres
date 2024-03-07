@extends('layouts.admin')
 
@section('title', 'Bank Account')
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
                                <h4>Bank Account</h4>
                            </div>
                            <div class="col-md-6 text-right col-12">
                                <button type="button" class="btn btn-info btn-lg openModal">Add Bank Account</button>
                            </div>
                        </div>
                       
                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableBankAccount" class="table w-100">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Bank Account</th>
                                        <th>Account Number</th>
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
<div id="modalBankAccount" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
      <div class="modal-content">
        <div class="modal-header bg-gradient-info">
          <h4 class="modal-title d-inline">Form Bank Account</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
         <form method="POST"  id="form-input">
                @csrf
            <input type="hidden" class="form-control form-control-sm inputtext" id="id" name="id">
            <div class="form-group"> 
                <select class="form-control select2 select2-hidden-accessible" id="type_payment" style="width: 100%;" name="type_payment" tabindex="-1" aria-hidden="true">
                <option disabled selected id="disabled">Type Of Payment</option>
                <option value="Account Number">Account Number</option>
                <option value="Virtual Account">Virtual Account</option>
                <option value="Bank Account">Bank Account</option>
                <option value="Address Account">Address Account</option>
                <option value="Account Credit">Account Credit</option>
            </select> </div>

            <div class="form-group">
                <label for="account_number">
                    Account Number<span style="color:red;">*</span>
                </label>
                <input type="text" class="form-control form-control-sm inputtext" id="account_number" name="account_number" required>
            </div>

            <button class="btn btn-success" id="simpan" type="submit">Save</button>

         </form>
        </div>
        </div>
  
    </div>
  </div>
  




@endsection

@push('after-script')
<script>
    jQuery(document).ready(function ($) {
        var table = $('#tableBankAccount').DataTable({
            processing: true,
            serverside: true,
            // scrollX: true,
            // "order": [
            //     [0, "asc"]
            // ],
            ajax: {
                url: '{{route("getBankAccount")}}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'type_payment',
                    name: 'type_payment',
                },
                {
                    data: 'account_number',
                    name: 'account_number',
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

        
        $('body').on('click', '.openModal', function () {
            $('#modalBankAccount').modal('show');
            $("#modalBankAccount .inputtext").val('');
            $("#type_payment").val('Type Of Payment');
            
        });

        $('body').on('click', '.updateItems', function () {
            var id = $(this).data('id');
            $.get("{{ route('bank-account.index' ) }}"+'/'+id, function (data) {
                $('#modalBankAccount').modal('show');
                $('#id').val(id);
                $('#type_payment').val(data.data.type_payment);
                $('#account_number').val(data.data.account_number);
            });
        });

        $('body').on('click', '.deleteItems', function (e) {
            e.preventDefault();
       
            var id = $(this).data('id');
            $.ajax({
                headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
                url: "{{ route('bank-account.index' ) }}" + '/' + id,
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
            var data = $('#form-input');
            var formData = data.serialize();

            $.ajax({
                type: "POST",
                url: "{{ route('bank-account.store') }}",
                data: formData,
        
                success: function (data) {
                    if (data.errors) {
                        console.log('error');
                    }
                    if (data.success) {
                        $('#modalBankAccount').modal('hide');
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
