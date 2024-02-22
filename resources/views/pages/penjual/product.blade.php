@extends('layouts.penjual')
@section('title', 'Detail product')
@section('content')
     <!-- Breadcrumb Section Begin -->
     <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="/{{request()->segment(1)}}"><i class="fa fa-home"></i> Home</a>
                        <span>Detail Product</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->
    
        <!-- Product Shop Section Begin -->
        <section class="product-shop spad page-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="product-pic-zoom">
                                    <img class="product-big-img" id="mainImage" src="{{ $detail->products->galleries[0]->photo }}" alt="{{$detail->products->name}}" style="height:auto;max-height: 670px;width:100%;"/>
                                </div>
                                <div class="product-thumbs" id="thumb">
                                    <div class="product-thumbs-track ps-slider owl-carousel">
                                        @if ($detail->products->galleries->count() > 1)
                                        @foreach ($detail->products->galleries as $galleries)
                                        <div class="pt" data-imgbigurl="{{$galleries->photo}}">
                                            <img src="{{$galleries->photo}}" alt="" />
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="product-details">
                                    <div class="pd-title">
                                        <span>{{$detail->products->categories->name}}</span>
                                        <h3>{{$detail->products->name}}</h3>
                                    </div>
                                    <div class="pd-desc">
                                        {!!$detail->products->description!!}

                                        <?php
                                        $basePrice = $detail->products->price + ($detail->products->price * $detail->stores->profit / 100);
                                        $promoPrice = ($detail->products->promo_price != 0) ? $basePrice - $detail->products->promo_price : 0;
                                        ?>

                                        @if($detail->products->promo_price != 0)
                                        <h4>
                                        <span class="m-0">{{
                                             '$' . number_format($basePrice, 2)
                                            }}</span>
                                        {{
                                            '$' . number_format($promoPrice, 2)
                                        }}
                                        </h4>
                                        @else
                                        <h4>
                                            {{
                                                '$' . number_format($basePrice, 2)
                                            }}
                                        </h4>
                                        @endif

                                    </div>
                                    <div class="row">
                                        <p style="color: #e7ab3c; margin-left:15px;">Stock : <span class="font-weight-bold">{{($detail->products->quantity < 0) ? 0 : $detail->products->quantity}}</span></p>
                                        <p style="color: #e7ab3c; margin-left:15px;">Views : <span class="font-weight-bold">{{$detail->products->total_views}}x</span></p>
                                        <p style="color: #e7ab3c; margin-left:15px;">Sold : <span class="font-weight-bold">{{$detail->products->total_sold}}x</span></p>
                                    </div>

                                    <p id="quantityError" style="color: red;"></p>
                                    <div class="quantity">
                                        <input type="number" id="quantityProduct" oninput="this.value = 
                                        !!this.value && Math.abs(this.value) >= 1 ? Math.abs(this.value) : 1" value="1" style="width: 70px; padding-left:14px;">

                                        @if (Auth::check())
                                            <a onclick="addToCart('addToCart',{{ $detail->products->id }},{{ $detail->products->quantity }}, '{{ $detail->products->name }}', '{{ $basePrice }}','{{ $promoPrice }}','{{ $detail->stores->slug }}','{{$detail->products->price * $detail->stores->profit / 100}}', '{{ $detail->products->galleries[0]->photo }}')" href="javascript:void(0)" class="primary-btn pd-cart mx-1 py-3 px-4">Add To Card</a>
                                            <a onclick="addToCart('buy',{{ $detail->products->id }},{{ $detail->products->quantity }}, '{{ $detail->products->name }}', '{{ $basePrice }}','{{ $promoPrice }}','{{ $detail->stores->slug }}','{{$detail->products->price * $detail->stores->profit / 100}}', '{{ $detail->products->galleries[0]->photo }}')" href="javascript:void(0)" class="primary-btn pd-cart py-3 px-4">Buy</a>
                                        @else
                                        <a href="{{ url('/logout?next=' . url()->full()) }}" class="primary-btn pd-cart mx-1 py-3 px-4">Add To Cart</a>
                                        <a href="{{ url('/logout?next=' . url()->full()) }}" class="primary-btn pd-cart mx-1 py-3 px-4">Buy</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Product Shop Section End -->
    
        <!-- Related Products Section End -->
        <div class="related-products spad">
            <div class="container">
                <div class="row mt-5 pt-2">
                    <div class="col-6 text-left align-self-center">
                        <h2 class="font-weight-bold" style="color: #e7ab3c;">Other products</h2>
                    </div>
                    <div class="col-6 text-right align-selft-center">
                        <a href="{{ '/' . request()->segment(1) . '/all-products' }}" class="primary-btn">All Product</a>
                    </div>
                </div>
                <div class="row mt-5">
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
        <!-- Related Products Section End -->
@endsection