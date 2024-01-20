@extends('layouts.admin')

@section('title','Product List')
@section('content')
    <div class="orders">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9 col-6">
                                <div class="box-title">Product List</div>
                            </div>
                            @if(Auth::user()->role == 1)
                            <div class="col-md-3 col-6">
                                <a href="{{route('products.create')}}" class="btn btn-primary w-100">Add Product</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Photo</td>
                                        <td>Name</td>
                                        <td>Price</td>
                                        <td>Discount</td>
                                        <td>Category</td>
                                        <td>Quantity</td>
                                        <td>Views</td>
                                        <td>Sold</td>
                                        <td style="width: 150px;">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ $product->galleries[0]->photo }}" data-lightbox="roadtrip"><img src="{{ $product->galleries[0]->photo }}" alt=""></a>
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            @if($product->promo_price != 0) <small class="line-through">{{ '$'.$product->price}}</small> {{ '$'.number_format($product->price - $product->promo_price,2)}} @else <span>{{ '$'.$product->price }}</span> @endif
                                        </td>
                                        <td>{{ ($product->promo_price == 0) ? '-' : '$'.$product->promo_price}}</td>
                                        <td>{{ $product->categories->name }}</td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{ $product->total_views }}</td>
                                        <td>{{ $product->total_sold }}</td>
                                        @if(Auth::user()->role == 1)
                                            <td>
                                                <a href="{{ route('products.gallery',$product->id) }}" class="btn btn-info btn-sm"><i class="fa fa-picture-o"></i></a>
    
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                                                
                                                <form action="{{ route('products.destroy', $product->id) }}" class="d-inline" method="POST" id="deleteForm">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $product->id }}')"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        @else
                                            @if (Request::is('products-list'))
                                                <td>
                                                <a href="{{ url('products/'.$product->id.'/delete') }}" class="btn btn-warning btn-sm"><i class="fa fa-times"></i> Return</a>
                                                </td>
                                            @else
                                            <td>
                                                <a href="{{ url('products/'.$product->id.'/take') }}" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Take</a>
                                            </td>
                                            @endif
                                        @endif

                                        @empty
                                        @if(Auth::user()->role == 1)
                                        <td>
                                            <td colspan="9" class="text-center"><strong>The data you are looking for does not exist</strong></td>
                                        </td>
                                        @else
                                            @if (Request::is('products-list'))
                                            <td>
                                                <td colspan="9" class="text-center"><strong>No products have been taken yet, you can take the product in the reseller product menu</strong></td>
                                            </td>
                                            @else
                                            <td>
                                                <td colspan="9" class="text-center"><strong>The product has been taken, there are no products from resellers</strong></td>
                                            </td>
                                            @endif
                                        @endif
                                        </tr>
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after-script')
<script>
    function confirmDelete(productId) {
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'If you delete this product, all transactions related to this product will be lost!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').action = "{{ url('products') }}/" + productId;
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>
@endpush
