@extends('layouts.penjual')
@section('title', $store)
@section('content')
    <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="hero-items owl-carousel">
            <div class="single-hero-items set-bg" data-setbg="{{asset('penjual/img/hero-1.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span>{{$store}}</span>
                            <h1>WELCOME</h1>
                            <p>
                                Welcome to {{ $store }}! Discover the beauty of the latest products and experience a pleasant online shopping experience. Explore our collection and enjoy special discounts.
                            </p>
                            <a href="{{ '/' . request()->segment(1) . '/all-products' }}" class="primary-btn">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-hero-items set-bg" data-setbg="{{asset('penjual/img/hero-2.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span>{{$store}}</span>
                            <h1>FIND YOUR STYLE</h1>
                            <p>
                                Find the latest styles and exclusive deals from {{ $store }}! Get quality products at special prices, just for you.
                            </p>
                            <a href="{{ '/' . request()->segment(1) . '/all-products' }}" class="primary-btn">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Women Banner Section Begin -->
    <section class="women-banner spad">
        <div class="container-fluid">
            <div class="row mt-5 pt-2">
                    <div class="col-6 text-left align-self-center">
                        <h2 class="font-weight-bold" style="color: #e7ab3c;">Best Selling Product</h2>
                    </div>
                    <div class="col-6 text-right align-selft-center">
                        <a href="{{ '/' . request()->segment(1) . '/all-products' }}" class="primary-btn">All Product</a>
                    </div>
            </div>
            <div class="row">
                <div class="col-lg-12 mt-5">
                    <div class="product-slider owl-carousel">
                        @forelse ($products as $product)
                        <div class="product-item">
                            <a href="{{ '/' . request()->segment(1) . '/' . $product->slug }}">
                            <div class="pi-pic">
                                <img src="{{ $product->galleries[0]->photo }}" alt="" />
                                <ul>
                                    <li class="quick-view">+ Quick View</li>
                                </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">{{ $product->categories->name }}</div>
                                    <h5>{{ $product->name }}</h5>
                                <div class="product-price">
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
                        <div class="row justify-content-center">
                            <strong class="text-center">Product is not available</strong>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Women Banner Section End -->
@endsection