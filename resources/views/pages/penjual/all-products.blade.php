@extends('layouts.penjual')
@section('title', 'All Product')
@section('content')

<!-- Women Banner Section Begin -->
<section class="women-banner spad">
    <div class="container-fluid">
        <div class="row mt-5 pt-2">
            <form action="{{ '/' . request()->segment(1) . '/all-products' }}" class="row col-12 mx-auto" method="GET">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="search">Search:</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search products">
                    </div>
                </div>
                <div class="col-12 col-md-4 align-self-center row d-flex justify-content-center">
                    <div class="col-5 text-center align-self-end">
                        <a href="{{ '/' . request()->segment(1) . '/all-products' }}" style=";color:#f78104;font-weight:bold;">Reset Filter</a>
                    </div>
                    <div class="col-5 p-0">
                        <button type="submit" class="btn btn-main btn-lg w-100" style="margin-bottom:-10px;" onclick="this.disabled=true;this.form.submit();">Filter</button>
                    </div>
     
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-12 mt-5 row justify-content-start mx-auto">
                {{-- card produk --}}
                @forelse ($products as $product)
                <div class="product-item col-12 col-md-4 col-lg-3">
                    <a href="{{ '/' . request()->segment(1) . '/' . $product->slug }}">
                        <div class="pi-pic justify-content-center d-flex">
                            {{-- Mengakses kolom photo langsung dari hasil kueri --}}
                            <img src="{{ asset($product->photo) }}" alt="" />
                            <ul>
                                <li class="quick-view">+ Quick View</li>
                            </ul>
                        </div>
                        <div class="pi-text">
                            <div class="catagory-name">{{ $product->category_name }}</div>
                            <h5>{{ $product->name }}</h5>
                            <div class="product-price">
                                {{-- Mengakses kolom price langsung dari hasil kueri --}}
                                @if($product->promo_price != 0)
                                <span>{{
                                        '$' . number_format($product->price + ($product->price * $profit / 100), 2)
                                    }}</span>
                                {{
                                        '$' . number_format($product->price + ($product->price * $profit / 100) - $product->promo_price, 2)
                                    }}
                                @else
                                {{
                                        '$' . number_format($product->price + ($product->price * $profit / 100), 2)
                                    }}
                                @endif
                                <div class="row justify-content-center mt-2">
                                    <p style="font-size: 15px">{{ $product->total_views }} Views</p>
                                    &nbsp;&nbsp;
                                    <p style="font-size: 15px">{{ $product->total_sold }} Sold</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="row col-12 justify-content-center">
                    <strong class="text-center">The data you are looking for does not exist</strong></td>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
<!-- Women Banner Section End -->
@endsection
