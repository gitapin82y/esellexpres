@extends('layouts.admin')
 
@section('title', 'Orders')
 @push('after-style')
 <style>
    /* th, td { white-space: nowrap; } */
    div.dataTables_wrapper {
        width:100%;
        margin: 0 auto;
    }

    .dtfc-fixed-right{
        background-color: rgba(255, 255, 255, 0.768);
    }
table{
    width: 100%;
}
 td,
 th {
    vertical-align: middle !important;
    white-space: wrap !important;
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
                        @if (Auth::user()->role != 1)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Purchase Price</strong> is the total that must be paid to the reseller. Make sure you have sufficient balance.
                            <br><strong>Total Payment</strong> is the amount you will get when the product order reaches the customer.
                            <hr>
                            <strong>Purchase Price</strong> and <strong>Total Payment</strong> include shipping costs
                            <hr>
                            <p class="mb-0 text-main">Confirm the order and wait until the product status is "The customer has received the order" to get benefits.</p>
                            <p class="mb-0 text-main">Profit per product obtained is <strong>{{$profit}}%</strong>, get lots of sales for additional profit from product sales!</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          @endif
                        <div class="row col-12 justify-content-between">
                            <div class="col-md-4 text-left col-6 align-self-center">
                                <h4>All product order lists</h4>
                            </div>
                            @if (Auth::user()->role != 1)
                            <div class="col-md-4 text-center col-6 align-self-center">
                                <label for="filterDate">Filter by Month:</label>
                                <select id="filterMonth" class="form-control">
                                    <option value="">Select Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-md-4 text-center text-md-right col-12">
                                <strong>Total profit</strong> <br> Taken <span style="color: #349e5a;"><strong> ${{$taken}}</strong></span>&nbsp; 
                                Hold <span style="color:#828282;"><strong>${{$hold}}</strong></span>
                            </div>
                            @else
                            <div class="col-md-4 text-right col-6 align-self-center">
                                <label for="filterDate">Filter by Month:</label>
                                <select id="filterMonth" class="form-control">
                                    <option value="">Select Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            @endif
                        </div>
                       
                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableTopup" class="table w-100">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th class="@if (Auth::user()->role != 1) d-none @endif">Buyer Name</th>
                                        <th class="@if (Auth::user()->role != 1) d-none @endif">Seller Name</th>
                                        <th>Purchase Price @if (Auth::user()->role != 1)<br><small>reseller</small>@endif</th>
                                        <th>Total Payment @if (Auth::user()->role != 1)<br><small>selling profit</small>@endif</th>
                                        <th>Total Product</th>
                                        <th>Purchase Date</th>
                                        <th>Status Product</th>
                                        <th>Detail Product</th>
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
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js
"></script>
<script>
    jQuery(document).ready(function($) {
        $('#mymodal').on('show.bs.modal',function(e){
            var button = $(e.relatedTarget);
            var modal = $(this);
            modal.find('.modal-body').load(button.data('remote'));
            modal.find('.modal-title').html(button.data('title'));
        });

        @if(session('failedConfirm'))
                Swal.fire({
                    title: "Insufficient Balance",
                    text: 'Your balance is not enough to make a profit on product sales',
                    icon: "warning",
                    confirmButtonColor: "#e7ab3c",
                    confirmButtonText: "Top Up Balance Now"
                    }).then((result) => {

                    if (result.isConfirmed) {
                        $('#topUpModal').modal('show');
                    }
                });
        @endif
  });
</script>

<div class="modal" id="mymodal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header d-flex">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <i class="fa fa-spinner fa-spin"></i>
        </div>
      </div>
    </div>
  </div>
<script>

       jQuery(document).ready(function ($) {
        var table = $('#tableTopup').DataTable({
            processing: true,
            serverside: true,
            order: [0, 'desc'],
            ajax: {
                url: '{{route("getIncomingOrders")}}',
            },
            columns: [{
                    data: 'uuid',
                    name: 'uuid',
                },
                 { data: 'buyer_name', name: 'buyer_name', className: 'text-center @if (Auth::user()->role != 1) d-none @endif' },
        { data: 'seller_name', name: 'seller_name', className: 'text-center @if (Auth::user()->role != 1) d-none @endif' },
                { data: 'seller_name', name: 'seller_name', className: 'text-center' }, // Kolom Nama Penjual
                { data: 'buyer_name', name: 'buyer_name', className: 'text-center' },
                {
                    data: 'transaction_total',
                    name: 'transaction_total',
                    render: function(data) {
                        return '<span style="color:#e7973c;"><strong>$'+data+'</strong></span>';
                    },
                },{
                    data: 'profit',
                    name: 'profit',
                     className: 'text-center'
                },{
                    data: 'total_quantity',
                    name: 'total_quantity',
                    className: 'text-center'
                },{
                    data: 'created_at',
                    name: 'created_at',
                },{
                    data: 'status',
                    name: 'status',
                    render : function(data){
                        if(data == 'The customer has received the order'){
                            return '<span style="color: #349e5a;">The customer has received the order</span>';
                        }else if(data == 'Reject order'){
                            return '<span style="color: #dc3545;">Reject order</span>';
                        }else{
                            return '<span style="color: #e7973c;">'+data+'</span>';
                        }
                    }
                },{
                    data: 'action',
                    name: 'action',
                    className: 'text-center'
                }
            ],

        });
        // end data table ajax

        $(document).on('change', '.filter-date', function () {
            var selectedDate = $(this).val();
            table.column(4).search(selectedDate).draw();
        });

         $('#filterMonth').change(function () {
    var selectedMonth = $('#filterMonth').val();
     if (selectedMonth) {
          var searchTerm = moment(selectedMonth, 'M').format('MMMM');
    table.column(4).search(searchTerm, true).draw();
    } else {
        table.column(4).search('').draw();
    }
});



    });

</script>

<script>
    function confirmDelete(transactionId) {
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'If you delete this transaction, the transaction history data will automatically be deleted for the seller or buyer!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Set action form dengan URL yang tepat
                document.getElementById('deleteForm').action = "{{ route('transactions.destroy', ':transactionId') }}".replace(':transactionId', transactionId);
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>

@endpush
{{-- end script khusus pada pages daftar anggota --}}
