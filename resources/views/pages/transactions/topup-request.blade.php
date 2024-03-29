@extends('layouts.admin')
 
@section('title', 'Top Up Balance')
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
                                <h4>Top Up Request History</h4>
                            </div>
                            <div class="col-md-6 text-right col-12">
                                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Top Up Balance</button>
                            </div>
                        </div>
                       
                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableTopup" class="table w-100">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Transaction</th>
                                        <th>Total</th>
                                        <th>Message</th>
                                        <th>Status</th>
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



<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-gradient-info">
          <h4 class="modal-title d-inline">Top Up</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
         <form action="{{ route('topup-balance.index') }}" method="POST"  id="form-modal" enctype="multipart/form-data" >
                @csrf

                Complete your payment and transfer to : 
                <div class="px-3 pt-2 my-2" style="background-color: #eff6ff">
                    @php
                        $items = DB::table('bank_accounts')->get();
                    @endphp
                    @foreach ($items as $item)
                    <h5 style="font-weight:bold;margin-top:10px;"> {{ $item->type_payment }}</h5> 
                    <div class="row col-12" style="margin-bottom:10px;">
                        <p class="account-number text-primary" id="accountNumber{{ $loop->index }}">{{ $item->account_number }}</p>
                        &nbsp;
                    <a class="copy-button" style="cursor: pointer;" data-clipboard-target="#accountNumber{{ $loop->index }}">(Click for copy)</a>
                    </div>
                    @endforeach
                </div>

                  <label for="total">
                      Total Top Up<span style="color:red;">*</span>
                  </label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                <input type="text" class="form-control h-auto form-control-sm inputtext" id="total" name="total" placeholder="0.00" oninput="handleInput(this)" required>
            </div>
            <div class="form-group">
                <label for="proof">
                    Proof<span style="color:red;">*</span>
                </label>
                <input type="file" class="form-control h-auto form-control-sm inputtext" id="proof" name="proof" required>
            </div>
            <div class="form-group">
                <label for="message">
                    Message
                </label>
                <input type="text" class="form-control form-control-sm inputtext" id="message" name="message">
            </div>
            <button class="btn btn-success" id="simpan" type="submit" onclick="this.disabled=true;this.form.submit();">Send Request</button>

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

       jQuery(document).ready(function ($) {
        var table = $('#tableTopup').DataTable({
            processing: true,
            serverside: true,
            // fixedColumns: {
            //     right: 1,
            //     left: 0,
            // },
            // scrollX: true,
            "order": [
                [0, "asc"]
            ],
            ajax: {
                url: '{{route("getTopup")}}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
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
                },
            ],

        });
        // end data table ajax

    });

</script>
@endpush
{{-- end script khusus pada pages daftar anggota --}}
