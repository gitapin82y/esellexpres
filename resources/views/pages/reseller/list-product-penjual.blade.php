@extends('layouts.admin')
 
@section('title', 'List Product Seller')
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
                        <div class="row justify-content-between">
                        <div class="col-md-4 text-left align-self-center col-12">
                            <h4>List Product, Store : <strong>{{$store}}</strong></h4>
                        </div>
                        <div class="col-12 col-md-4 text-right">
    
                            <div class="breadcrumb-text product-more">
                                <a href="/seller-list"><i class="fa fa-back"></i> List Seller</a>
                                /
                                <span>List Product Seller</span>
                            </div>
                           
                        </div>
                    </div>

                    </div>

                    <div class="card-body pb-5 pt-2 w-100">
                        <div class="table-responsive table-invoice overflow-hidden">
                            <table id="tableListPenjual" class="table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Views</th>
                                        <th>Sold</th>
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
        var table = $('#tableListPenjual').DataTable({
            processing: true,
            serverside: true,
            // scrollX: true,
            "order": [
                [0, "asc"]
            ],
            ajax: "{{ route('getListProductPenjual', $store) }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { 
                    data: 'galleries', 
                    name: 'galleries',
                    render: function(data) {
                        return '<a href="'+data[0].photo+'" data-lightbox="roadtrip"><img src="'+data[0].photo+'" width="90px"></a>';
                    }
                },
                { data: 'name', name: 'name' },
                { data: 'price', name: 'price' },
                { data: 'promo_price', name: 'promo_price' },
                { data: 'categories.name', name: 'categories.name' },
                { data: 'quantity', name: 'quantity' },
                { data: 'total_views', name: 'total_views' },
                { data: 'total_sold', name: 'total_sold' },
                { data: 'action', name: 'action' },
            ],
            // Additional configuration options if needed
        });

    });

    // $('#modal').modal('show');
      // aksi ajax jika tombol edit di klik
    

</script>

{{-- @include('sweetalert::alert') --}}
@endpush
{{-- end script khusus pada pages daftar anggota --}}
