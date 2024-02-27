@extends('layouts.penjual')
@section('title', 'Status Product')
@push('before_style')
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css
" rel="stylesheet">
<style>
/* CSS untuk scrollbar horizontal */
.table-responsive {
    overflow-x: scroll;
}
.table-responsive table{
    width: 100%;
}
/* Optional: Mengatur style scrollbar */
.table-responsive::-webkit-scrollbar {
    height: 12px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background-color: #e7ab3c;
    border-radius: 6px;
}
 td,
 th {
    vertical-align: middle !important;
}

.table-responsive::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

.footer-style{
            margin-bottom: -40px;
}
</style>
@endpush
@section('content')
     <!-- Breadcrumb Section Begin -->
     <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="/{{request()->segment(1)}}"><i class="fa fa-home"></i> Home</a>
                        <span>Status Product</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="cart-table">
                            <div class="table-responsive">
                                <table id="shoppingCardTable" class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 130px;">Transaction ID</th>
                                            <th style="width: 110px;">Total Product</th>
                                            <th style="width: 110px;">Total Payment</th>
                                            <th style="width: 150px;">Purchase Date</th>
                                            <th style="width: 130px;">Status Product</th>
                                            <th style="width: 130px;">Detail Product</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $item)
                                            <tr>
                                                <td>
                                                    {{$item->uuid}}
                                                </td>
                                                <td>
                                                    {{$item->total_quantity}}
                                                </td>
                                                <td style="color: #e7ab3c;font-weight:200;">
                                                  <b>${{$item->profit}}</b> 
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('F j, Y') }}
                                                </td>
                                                <td style="font-weight:bold;@if($item->status == 'Reject order') color: #dc3545; @elseif($item->status == 'The customer has received the order') color: #59d587; @else color: #e7ab3c; @endif">
                                                    {{$item->status}}
                                                </td>
                                                <td>
                                                    @if ($item->status == 'The order is being delivered to the destination address')
                                                    <a href="{{ route('transactions.status', ['id' => $item->id, 'status' => 'The customer has received the order']) }}" class="btn btn-success my-1">
                                                        <i class="fa fa-check"></i>
                                                        receive orders
                                                    </a>
                                                    @endif
                                                    <a href="#mymodal" data-remote="{{ route('transaction.show',$item->id) }}" data-toggle="modal" data-target="#mymodal" data-title="Detail Transaksi <b>{{ $item->uuid }}</b>" class="btn btn-main my-1"><i class="fa fa-eye"></i> View Detail</a>
                                                </td>
                                            </tr>
                                        @empty
                                        <td>
                                            <td colspan="4" class="text-center">Product Purchase Transaction is Empty</td>
                                        </td>
                                         
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->


    {{-- modal success --}}

@endsection

@push('before_script');
<script>
    jQuery(document).ready(function($) {
  $('#mymodal').on('show.bs.modal',function(e){
      var button = $(e.relatedTarget);
      var modal = $(this);
      modal.find('.modal-body').load(button.data('remote'));
      modal.find('.modal-title').html(button.data('title'));
  });
  });
</script>

<div class="modal" id="mymodal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
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
@endpush