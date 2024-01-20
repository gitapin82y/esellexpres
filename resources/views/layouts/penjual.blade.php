<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    @php
        use App\Models\Store;
        $store = Store::where('slug',request()->segment(1))->first();
    @endphp
    <link rel="icon" type="image/png" href="{{ optional($store)->logo ? asset($store->logo) : asset('images/logo.png') }}" sizes="32x32" style="object-fit: contain;">



    <title>@yield('title')</title>
    
    @include('includes.penjual.style')
    @stack('before_style')
    <style>
    .ti-close {
        color: #d85151;
        font-weight: bold;
        cursor: pointer;
    }
    .select-items a:hover{
        color: #f78104 !important;
    }
    </style>
</head>

<body>
       <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @include('includes.penjual.navbar')

            @yield('content')

    @include('includes.penjual.footer')
    @include('sweetalert::alert')
    
    @include('includes.penjual.script')
    @stack('before_script')
    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js
"></script>
    
    <script>

document.addEventListener('DOMContentLoaded', function() {
    updateData();
});


function addToCart(type,productId,stock,productName,productPrice,promoPrice,slugStore,profit, productImage) {
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if(stock <= 0){
        Swal.fire({
            title: "Stock Still Empty",
            text: "As soon as possible we will be ready to stock the product again",
            icon: "warning",
            confirmButtonColor: "#e7ab3c",
        })
        return;
    }
    
    var quantity = parseInt(document.getElementById('quantityProduct').value);
    var quantityError = document.getElementById('quantityError');
    if(quantity > stock){
        quantityError.textContent = 'Maximum order '+stock;
        return;
    }else{
        quantityError.textContent = '';
    }

    const userId = getUserId();
    // const existingProduct = cart.find(item => item.id === productId);
    const existingProductIndex = cart.findIndex(item => item.id === productId && item.userId === userId && item.slugStore === slugStore);

    if (existingProductIndex !== -1) {
        // existingProduct.quantity += quantity;
        cart[existingProductIndex].quantity += quantity;
    } else {
        cart.push({
            id: productId,
            userId: userId,
            slugStore: slugStore,
            name: productName,
            price: productPrice,
            promoPrice: promoPrice,
            profit: profit,
            img: productImage,
            quantity: quantity,
        });
    }
    // // Cek apakah produk sudah ada di keranjang
    // const existingProduct = cart.find(item => item.id === productId);

    // if (existingProduct) {
    //     existingProduct.quantity += quantity;
    // } else {
    //     const userId = getUserId();
    //     cart.push({
    //         id: productId,
    //         userId: userId,
    //         name: productName,
    //         price: productPrice,
    //         promoPrice: promoPrice,
    //         img: productImage,
    //         quantity: quantity,
    //     });
    // }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateData();

    if(type == 'buy'){
        window.location.href = '/'+slugStore+'/shopping-cart';
    }else{
        Swal.fire({
        title: "Product Added Successfully",
        text: "Check Shopping Cart and Checkout Now",
        icon: "success",
        confirmButtonColor: "#e7ab3c",
        });
    }
}

function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const userId = getUserId();
    const slugStore = getSlugStore();

    // Hapus produk dari keranjang berdasarkan ID produk dan ID pengguna
    cart = cart.filter(item => item.id !== productId && item.userId === userId && item.slugStore === slugStore);


    localStorage.setItem('cart', JSON.stringify(cart));
    updateData();
    updateCheckoutTable();
}

function updateData() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    let totalPrice = 0;
    let totalQuantity = 0;

    const cartListContainer = document.getElementById('cart-list-container');
    cartListContainer.innerHTML = '';

    const userId = getUserId();
    const slugStore = getSlugStore();

    // Filter item berdasarkan userId
    const userCart = cart.filter(item => item.userId === userId && item.slugStore === slugStore);

    if (userCart.length === 0) {
        cartListContainer.innerHTML = '<p class="text-center pb-2">Product data is empty.</p>';
    } else {
        userCart.forEach(item => {

            const price = (item.promoPrice != 0 ) ? item.promoPrice : item.price;

            totalPrice += price * item.quantity;
            totalQuantity += item.quantity;

            const listItem = document.createElement('tr');
            listItem.innerHTML = `
            <tr>
            <td class="si-pic p-0 pb-2" style="width:60px;"><img src="${item.img}" style="border-radius:4px; width:100%;"></td>
            <td class="si-text">
                <div class="product-selected">
                    <p>${(item.promoPrice != 0) ?
                        `<span style="font-size:12px;color:#b2b2b2;text-decoration:line-through">$${parseFloat(item.price).toFixed(2)}</span> $${parseFloat(item.promoPrice).toFixed(2)}`
                        : `$${parseFloat(item.price).toFixed(2)}`} x ${item.quantity}</p>
                    <h6>${item.name}</h6>
                </div>
            </td>
            <td class="si-close" onclick="removeFromCart(${item.id})">
                <i class="ti-close"></i>
            </td>
        </tr>
            `;
            cartListContainer.appendChild(listItem);
        });
    }
    document.getElementById('total-price').innerText =  "$" + totalPrice.toFixed(2);
    document.getElementById('cart-count').innerText = totalQuantity;

}

function getUserId() {
    // Pastikan Anda memuat variabel ini dari server ke dalam skrip Anda
    const userId = {{ auth()->id() }}; // Ini hanya contoh sintaks, sesuaikan dengan kebutuhan Laravel Anda
    return userId;
}

function getSlugStore() {
        const pathUrl = window.location.pathname;
const urlSegments = pathUrl.split('/');
const slugStore = urlSegments[1];
    return slugStore;
}

    </script>
</body>
</html>
