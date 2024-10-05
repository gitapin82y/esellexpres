@extends('layouts.penjual')
@section('title', 'Shopping Card')
@push('before_style')
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css
" rel="stylesheet">
<style>
        #shoppingCardTable {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        #shoppingCardTable th {
            background-color: #f2f2f2;
        }

        #shoppingCardTable img {
            max-width: 50px;
            border-radius: 4px;
        }

        #shoppingCardTable input[type="number"] {
            width: 50px;
        }
        .ti-close{
            color: #d85151;
            font-weight: bold;
            cursor: pointer;
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
                        <span>Shopping Cart</span>
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
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="cart-table">
                                <table id="shoppingCardTable">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="user-checkout">
                                <form id="myForm" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="hidden" class="form-control" id="email" aria-describedby="email" value="{{Auth::user()->email}}">
                                            <input type="hidden" class="form-control" id="user_id" aria-describedby="user_id" value="{{Auth::user()->id}}">
                                            <div class="form-group">
                                                <label for="namaLengkap">Full Name</label>
                                                <input type="text" class="form-control" id="namaLengkap" aria-describedby="namaHelp" value="{{Auth::user()->name}}" placeholder="Masukan Nama">
                                                <span class="error-message" id="error-namaLengkap"></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="noHP">Phone</label>
                                                <input type="number" class="form-control" id="noHP" aria-describedby="noHPHelp" value="{{Auth::user()->phone}}" placeholder="Masukan No. HP">
                                                <span class="error-message" id="error-noHP"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <select id="selectOption" class="form-control" required>
                                        <option hidden value="">Select Delivery Service</option>
                                        @php
                                            $delivery_services = DB::table('delivery_services')->get();
                                        @endphp
                                        @foreach($delivery_services as $delivery_service)
                                            <option value="{{ $delivery_service->id }}">{{ $delivery_service->name }}</option>
                                        @endforeach
                                    </select>    
                                    <span class="error-delivery" id="error-delivery"></span>         
                                    <div class="mb-2"></div>                       
                                    <div class="form-group">
                                        <label for="alamatLengkap">Shipping Address</label>
                                        <textarea class="form-control" id="alamatLengkap" placeholder="Enter the complete address along with Province, City, District and Postal Code" rows="3" required></textarea>
                                        <span class="error-message" id="error-alamatLengkap"></span>
                                    </div>                                  
                            </div>
                            <div class="proceed-checkout">
                                <ul>
                                    <li class="subtotal mt-3">Subtotal <span id="total-price-shopping-cart">$0.00</span></li>
                                    <li class="subtotal mt-3">Tax & Shipping Cost <span id="taxAmount">0</span></li>
                                    <li class="subtotal mt-3">Total Amount <span id="totalAmount">$0.00</span></li>
                                </ul>
                                <button type="submit" id="checkout-product" class="proceed-btn w-100">MAKE PAYMENT</a>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->


    {{-- modal success --}}

@endsection

@push('before_script')
 
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js
"></script>
<script>
    function updateCheckoutTable() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let checkoutTable = document.getElementById('shoppingCardTable').getElementsByTagName('tbody')[0];
        checkoutTable.innerHTML = '';

        // Gantilah ini dengan cara Anda mendapatkan ID pengguna saat ini
        const userId = getUserId();
        const slugStore = getSlugStore();

        // Filter item berdasarkan userId
        const userCart = cart.filter(item => item.userId === userId && item.slugStore === slugStore);

        let profitPrice = 0;
        let normalPrice = 0;

        if (userCart.length === 0) {
            // Cart kosong, tampilkan pesan
            checkoutTable.innerHTML = '<tr><td colspan="6">Product data is empty.</td></tr>';
        } else {
            userCart.forEach(item => {
            // Membuat baris untuk setiap produk
            let row = checkoutTable.insertRow();

            // Menambahkan kolom untuk gambar produk
            let imgCell = row.insertCell(0);
            let img = document.createElement('img');
            img.src = item.img;
            imgCell.appendChild(img);

            // Menambahkan kolom untuk nama produk
            let nameCell = row.insertCell(1);
            nameCell.textContent = item.name;

            // Menambahkan kolom untuk harga produk
            let priceCell = row.insertCell(2);

            // Menambahkan kolom untuk harga promo produk
            let originalPrice = parseFloat(item.price).toFixed(2);
            let promoPrice = parseFloat(item.promoPrice).toFixed(2);
            if (item.promoPrice != 0) {
                priceCell.innerHTML = `<span style="font-size:12px;color:#b2b2b2;text-decoration:line-through">$${originalPrice}</span><span style="color:#e7ab3c;"> $${promoPrice}</span>`;
            } else {
                priceCell.innerHTML = `<span style="color:#e7ab3c;">$${originalPrice}</span>`;
            }

             // Menambahkan kolom untuk quantity produk
             let quantityCell = row.insertCell(3);
              quantityCell.textContent = item.quantity;

              // QUANTITY INPUT UPDATE
            // let quantityCell = row.insertCell(3);
            // let quantityInput = document.createElement('input');
            // quantityInput.type = 'number';
            // quantityInput.value = item.quantity;
            // quantityInput.min = 1;
            // quantityInput.addEventListener('change', function () {
            //     updateQuantity(item.id, parseInt(this.value, 10));
            // });
            // quantityCell.appendChild(quantityInput);

            // Menambahkan kolom untuk total produk
            let totalCell = row.insertCell(4);
            let total = item.promoPrice != 0 ? item.promoPrice * item.quantity : item.price * item.quantity;
            totalCell.innerHTML = '<span style="color:#e7ab3c;">$'+parseFloat(total).toFixed(2)+'</span>' ;

            profitPrice += total;
            normalPrice += total - item.profit * item.quantity;

            // Menambahkan kolom untuk tombol hapus
            let deleteCell = row.insertCell(5);
            let deleteButton = document.createElement('i');
                deleteButton.className = 'si-close';
            deleteButton.onclick = function() {
                deleteProduct(item.id);
            };
            deleteButton.innerHTML = '<i class="ti-close"></i>';
            deleteCell.appendChild(deleteButton);
        });
        };

        // Persentase pajak
        var taxRate = {!! json_encode($shipping->fee)!!};

        // Hitung total keseluruhan
        var totalProfitPrice = (parseFloat(profitPrice) + parseFloat(taxRate)).toFixed(2);
        var totalNormalPrice = (parseFloat(normalPrice) + parseFloat(taxRate)).toFixed(2);

        document.getElementById('total-price-shopping-cart').innerText =  "$" + parseFloat(profitPrice).toFixed(2);
        document.getElementById('taxAmount').innerText = "$" + parseFloat(taxRate).toFixed(2);
        document.getElementById('totalAmount').innerText =  "$" + totalProfitPrice;
        return {
            total: totalNormalPrice,
            profit: totalProfitPrice,
            tax: taxRate,
        };
    }

    function deleteProduct(productId) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        // Gantilah ini dengan cara Anda mendapatkan ID pengguna saat ini
        const userId = getUserId();
        const slugStore = getSlugStore();

        // Hapus produk dari keranjang berdasarkan ID produk dan ID pengguna
        cart = cart.filter(item => item.id !== productId && item.userId === userId && item.slugStore === slugStore);

        // let updatedCart = cart.filter(item => item.id !== productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCheckoutTable();
        updateData();
    }

    // Panggil fungsi untuk memperbarui tabel saat halaman dimuat
    updateCheckoutTable();

    function validateForm() {
        var isValid = true;

        // Validasi Nama Lengkap
        var namaLengkap = $('#namaLengkap').val();
        if (namaLengkap === '') {
            $('#error-namaLengkap').text('Full name cannot be empty').css('color', 'red');
            isValid = false;
        } else {
            $('#error-namaLengkap').text('');
        }

        var delivery = $('#selectOption').val();
        if (delivery === '') {
            $('#error-delivery').text('Delivery cannot be empty').css('color', 'red');
            isValid = false;
        } else {
            $('#error-delivery').text('');
        }

        // Validasi No. HP
        var noHP = $('#noHP').val();
        if (noHP === '') {
            $('#error-noHP').text('Phone cannot be empty').css('color', 'red');
            isValid = false;
        } else {
            $('#error-noHP').text('');
        }

        // Validasi Alamat Lengkap
        var alamatLengkap = $('#alamatLengkap').val();
        if (alamatLengkap === '') {
            $('#error-alamatLengkap').text('Shipping address cannot be empty').css('color', 'red');
            isValid = false;
        } else {
            $('#error-alamatLengkap').text('');
        }

        return isValid;
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


    $('body').on('click', '#checkout-product', function (e) {
    e.preventDefault();
    
    $(this).prop('disabled', true).addClass('loading');

    // Setup Ajax headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    // Validasi formulir di sini menggunakan fungsi validateForm()
    if (!validateForm()) {
        $(this).prop('disabled', false).removeClass('loading');
        return false;
    }

    var nilaiTotal = updateCheckoutTable();

    // Mengambil data produk dari local storage (contoh: cart)
    var cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Gantilah ini dengan cara Anda mendapatkan ID pengguna saat ini
        const userId = getUserId();
        const slugStore = getSlugStore();


        // Filter produk berdasarkan user ID
        const userCart = cart.filter(item => item.userId === userId && item.slugStore === slugStore);

        const totalQuantity = userCart.reduce((total, item) => total + parseInt(item.quantity || 0, 10), 0);

    if (userCart.length === 0) {
        Swal.fire({
            title: "Your shopping basket is still empty",
            text: "We have other interesting product promotions, shop now!",
            icon: "warning",
            confirmButtonColor: "#e7ab3c",
            })
            return;
    } 
    var store_id = {!! json_encode($store->id)!!};
    var formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: $('#namaLengkap').val(),
            phone: $('#noHP').val(),
            email: $('#email').val(),
            user_id: $('#user_id').val(),
            delivery_service_id: parseInt($('#selectOption').val(),11),
            address: $('#alamatLengkap').val(),
            store_id:store_id,
            total_quantity: totalQuantity,
            transaction_total: parseFloat(nilaiTotal.total).toFixed(2),
            profit: parseFloat(nilaiTotal.profit).toFixed(2),
            tax: parseFloat(nilaiTotal.tax).toFixed(2),
            products: userCart
        };
    
    $.ajax({
        type: 'POST',
        url: 'shopping-cart/checkout',
        data: formData,
        dataType: 'json',
        success: function (response) {
       
            if(!response.product_empty){
            if(response.checkout){
                // Mengambil data produk dari local storage (contoh: cart)
                var cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Gantilah ini dengan cara Anda mendapatkan ID pengguna saat ini
                const userId = getUserId();
                const slugStore = getSlugStore();

                // Hapus item dari localStorage berdasarkan user ID
                cart = cart.filter(item => item.userId !== userId || item.slugStore !== slugStore);
                localStorage.setItem('cart', JSON.stringify(cart));

                Swal.fire({
                    title: "Paid Successfully",
                    text: "Please wait for the latest status update from us on the product checkout history page",
                    icon: "success",
                    confirmButtonColor: "#e7ab3c",
                    allowOutsideClick: false,
                    confirmButtonText: "View Product Status"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var nameStore = "{{ request()->segment(1) }}";
                        var halamanTujuan = "/" + nameStore + "/status-product";
                        window.location.href = halamanTujuan;
                    }
                });
            }else{
                Swal.fire({
                    title: "Insufficient Balance",
                    text: "Before making a purchase, please top up your balance first",
                    icon: "warning",
                    confirmButtonColor: "#e7ab3c",
                    confirmButtonText: "Top Up Balance"
                    }).then((result) => {

                    if (result.isConfirmed) {
                        $('#topUpModal').modal('show');
                    }
                });
            }
            }else{
                Swal.fire({
                    title: "The product is no longer available",
                    html: 'Sorry, the product <strong>'+response.product_name+'</strong> you selected is no longer available or is out of stock',
                    icon: "warning",
                    confirmButtonColor: "#e7ab3c",
                    allowOutsideClick: false,
                    confirmButtonText: "Shopping again"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        // Mengambil data produk dari local storage (contoh: cart)
                        var cart = JSON.parse(localStorage.getItem('cart')) || [];

                        // Gantilah ini dengan cara Anda mendapatkan ID pengguna saat ini
                        const userId = getUserId();
                        const slugStore = getSlugStore();

                        // Hapus item dari localStorage berdasarkan user ID
                        cart = cart.filter(item => item.userId !== userId || item.slugStore !== slugStore);
                        localStorage.setItem('cart', JSON.stringify(cart));

                        var nameStore = "{{ request()->segment(1) }}";
                        var halamanTujuan = "/" + nameStore + "/all-products";
                        window.location.href = halamanTujuan;
                    }
                });
            }
        },
        error: function (error) {
            Swal.fire({
                    title: "Sorry, there are transaction problems",
                    text: "There may be products at checkout that have been deleted by the owner, please checkout again",
                    icon: "warning",
                    confirmButtonColor: "#e7ab3c",
                    allowOutsideClick: false,
                    confirmButtonText: "Shopping again"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        // Mengambil data produk dari local storage (contoh: cart)
                        var cart = JSON.parse(localStorage.getItem('cart')) || [];

                        // Gantilah ini dengan cara Anda mendapatkan ID pengguna saat ini
                        const userId = getUserId();
                        const slugStore = getSlugStore();

                        // Hapus item dari localStorage berdasarkan user ID
                        cart = cart.filter(item => item.userId !== userId || item.slugStore !== slugStore);
                        localStorage.setItem('cart', JSON.stringify(cart));

                        var nameStore = "{{ request()->segment(1) }}";
                        var halamanTujuan = "/" + nameStore + "/all-products";
                        window.location.href = halamanTujuan;
                    }
                });
            // Tampilkan alert jika gagal
            console.log('Error:', error);
            // alert('An error occurred while making a transaction.');
            // // Lakukan tindakan lain jika diperlukan
        }
    });
    $(this).prop('disabled', false).removeClass('loading');

    });

    function getUserId() {
    // Pastikan Anda memuat variabel ini dari server ke dalam skrip Anda
    const userId = {{ auth()->id() }}; // Ini hanya contoh sintaks, sesuaikan dengan kebutuhan Laravel Anda
    return userId;
    }

</script>
@endpush