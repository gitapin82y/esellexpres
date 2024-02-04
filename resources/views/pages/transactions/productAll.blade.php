<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Esellexpress</title>
        @php
        use App\Models\Store;
        $store = Store::whereHas('users', function ($query) {
            $query->where('role', 1);
        })->with('users')->first();
        @endphp
          <link rel="icon" type="image/png" href="{{ optional($store)->logo ? asset($store->logo) : asset('images/logo.png') }}" sizes="32x32" style="object-fit: contain;">

        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        @include('includes.penjual.style')
        <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,600;1,600&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300;0,500;0,600;0,700;1,300;1,500;1,600;1,700&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,400;1,400&amp;display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{asset('homepage/css/styles.css')}}" rel="stylesheet" />
          
        <style>
            .nav-item{
                background-color: white;
            }
            .btn-main{
                background-color: #f78104;
                border: none;
                color: white;
            }
            .btn-main:hover{
                background-color:#ffa13d;
                color: white;
            }
            .banner{
                background-image: url('images/banner.png');
                width: 100%;
                background-size: cover;
                height: auto;
            }
            .logo-homepage img{
                    max-height: 50px; 
                }   
            @media (max-width: 576px) { 
                .logo-homepage img{
                    max-height: 30px; 
                }   
             }
             
             .namaClass {
    text-decoration: none; /* Remove underline from the link */
    position: relative;
}
.namaClass:hover .cart-hover {
    display: block;
}

.namaClass:active .cart-hover {
    display: block; /* Keep the dropdown visible on click */

}
td {
    vertical-align: middle !important;
}

.cart-hover {

    display: none;
    position: absolute;
    background-color: #fff;
    box-shadow: 0 0 30px rgba(50, 50, 50, 0.068);
    padding: 10px;
    z-index: 1;
    transition: display 1s ease; /* Add transition for a smooth effect */
}
.select-items a{
    color: #212529;
    text-decoration:none;
}
.select-items a:hover{
    color: #f78104;
    text-decoration:none;
}
.user-avatar {
    float: right;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    object-fit: cover;
}


        </style>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm" id="mainNav">
            <div class="container px-2 px-md-5">
                <a class="navbar-brand fw-bold h-100 logo-homepage" href="#page-top">
                    <img src="{{ optional($store)->logo ?? asset('images/logo.png') }}" alt="Logo">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="bi-list"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto me-4 my-3 my-lg-0">
                        <li class="nav-item"><a class="nav-link me-lg-3" href="/#home">Home</a></li>
                        <li class="nav-item"><a class="nav-link me-lg-3" href="#benefit">Benefit</a></li>
                        <li class="nav-item"><a class="nav-link me-lg-3" href="#product">Product</a></li>
                        <li class="nav-item"><a class="nav-link me-lg-0" href="#contact">Contact Us</a></li>
                    </ul>
                    @if(Auth::check())
                        <li class=" namaClass nav-link d-flex">
                            <div class="nama d-inline" style="text-transform: capitalize;padding-top:8px;">
                                {{ Auth::user()->name }}
                            </div>
                            &nbsp;
                            <img class="user-avatar" src="{{asset(Auth::user()->avatar)}}" alt="User Avatar">
                    
                            {{-- dropdown --}}
                            <div class="cart-hover" style="width: 160px; margin-right: 40px; margin-top: 40px;">
                                <div class="select-items mb-2 ms-2">
                                    @if (Auth::user()->role != 3)
                                        <a href="/dashboard">Dashboard</a>
                                        <br>
                                    @endif
                                    @if (Auth::user()->role == 3)
                                    <a href="/join-seller">Join As Seller</a>
                                    <div class="my-2"></div>
                                    <a href="/status-product">My Order</a>
                                    @endif
                                    <div class="my-2"></div>
                                    <a href="/change-password">Reset Password</a>
                                    <div class="my-2"></div>
                                    <a href="{{ url('/logout')}}">Logout</a>
                                </div>
                            </div>
                        </li>
                    
                    
                    
                    @else
                    <a href="/login" class="btn btn-main rounded-pill px-4 py-2 mb-lg-0">
                        <span class="d-flex align-items-center">
                            <span class="small">Login</span>
                        </span>
                    </a>
                    @endif
                </div>
            </div>
        </nav>
        <!-- Mashead header-->
        <div class="container mt-5 pt-5">
            <div class="breacrumb-section">
                <div class="container">
                    <div class="row pt-3">
                        <div class="col-lg-12">
                            <div class="breadcrumb-text product-more">
                                <a href="/" style="text-decoration: none;color:#e79934;"><i class="fa fa-home"></i> Home</a>
                                <span>Status Product</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-5">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="cart-table">
                            <div class="table-responsive">
                                <table id="shoppingCardTable" class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 130px;">Store</th>
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
                                                    <a href="{{ url($item->stores->name) }}" style="color: #e7ab3c;"> {{$item->stores->name}}</a>
                                                </td>
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
                                                <td style="font-weight:bold;@if($item->status == 'The customer has received the order') color: #59d587; @else color: #e7ab3c; @endif">
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


        <!-- App badge section-->
        <section style="background-color: #ff9c2c" id="contact">
            <div class="container px-5">
                <div class="row">
                    @php
                    use App\Models\User;
                    $user = User::where('role',1)->first();
                @endphp
                <div class="col-12 col-md-3 ps-md-4 pt-4 text-white">
                <h2 class="text-white font-alt mb-4">Contact Us</h2>
                <hr>
                    <h4 class="font-alt">Email</h4>
                    <p><i class="fa-solid fa-envelope"></i> {{ $user->email ?? 'cs@esellexpress.com' }}</p>
                    {{-- <h4 class="font-alt">Telepone</h4>
                    <p><i class="fa-solid fa-phone"></i> {{ $user->phone ?? '085281147618' }}</p> --}}
                </div>
                <div class="col-12 col-md-9 ps-md-5 pt-4">
                    <form action="send-mail" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="floatingInput" required placeholder="your name">
                            <label for="floatingInput">Your Name</label>
                          </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="floatingInput" required placeholder="name@example.com">
                            <label for="floatingInput">Your Email</label>
                          </div>
                          <div class="form-floating">
                            <textarea class="form-control" name="message" placeholder="Leave a comment here" required id="floatingTextarea2" style="height: 100px"></textarea>
                            <label for="floatingTextarea2">Your Comments</label>
                          </div>
                                 
                <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center">
                    <button type="submit" class="btn btn-outline-light py-3 px-4 w-100 mt-3">Send Message</button>
                </div>
                    </form>
                </div>
            </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="bg-black text-center py-3">
            <div class="container px-2">
                <div class="text-white small">
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        All rights reserved | Esellexpress
                    </div>
            </div>
        </footer>

        @include('sweetalert::alert')
        @include('includes.penjual.script')
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
          <div class="modal-dialog">
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
 
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{asset('homepage/js/scripts.js')}}"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
</html>
