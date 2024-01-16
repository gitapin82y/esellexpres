<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Esellexpres</title>
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
        </style>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm" id="mainNav">
            <div class="container px-5">
                <a class="navbar-brand fw-bold h-100" href="#page-top">
                    <img src="{{ optional($store)->logo ?? asset('images/logo.png') }}" style="max-height: 50px" alt="Logo">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="bi-list"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto me-4 my-3 my-lg-0">
                        <li class="nav-item"><a class="nav-link me-lg-3" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link me-lg-3" href="#benefit">Benefit</a></li>
                        <li class="nav-item"><a class="nav-link me-lg-3" href="#product">Product</a></li>
                        <li class="nav-item"><a class="nav-link me-lg-0" href="#product">Contact Us</a></li>
                    </ul>
                    @if(Auth::check() && Auth::user()->role == 3)
                    <a href="/join-seller" class="btn btn-main rounded-pill px-4 py-2 mb-lg-0">
                        <span class="d-flex align-items-center">
                            <span class="small">Join As Seller</span>
                        </span>
                    </a>
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
            <!-- Hero Section Begin -->
    <section class="hero-section mt-n2 pb-0">
        <div class="hero-items owl-carousel">
            <div class="single-hero-items set-bg" data-setbg="{{asset('penjual/img/hero-1.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span>{{ $store->name ?? 'esellexpres' }}</span>
                            <h1>WELCOME</h1>
                            <p class="mb-4">
                                Welcome to {{ $store->name ?? 'esellexpres' }}! Get profits of up to tens of percent per product from sales of the products we have provided, login and register as a seller now!
                            </p>
                            @if(Auth::check() && Auth::user()->role == 3)
                            <a href="/join-seller" class="primary-btn text-decoration-none py-3 px-4 mt-1">Join As Seller</a>
                            @else
                            <a href="/login" class="primary-btn text-decoration-none py-3 px-4 mt-1">Login Now</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-hero-items set-bg" data-setbg="{{asset('penjual/img/hero-2.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span>{{ $store->name ?? 'esellexpres' }}</span>
                            <h1>JOIN NOW</h1>
                            <p class="mb-4">
                                Join {{ $store->name ?? 'esellexpres' }}  for an online shop business from the products we provide, login and register as a seller now!
                            </p>
                            @if(Auth::check() && Auth::user()->role == 3)
                            <a href="/join-seller" class="primary-btn text-decoration-none py-3 px-4 mt-1">Join As Seller</a>
                            @else
                            <a href="/login" class="primary-btn text-decoration-none py-3 px-4 mt-1">Login Now</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="benefit">
        <div class="container">
            <div class="row align-items-center">
                <div class="row text-center">
                    <h3 class="display-6 lh-1 mb-5">Benefits of joining us</h3>
                </div>
                        <div class="row col-12">
                            <div class="col-md-3">
                                <!-- Feature item-->
                                <div class="text-center">
                                    <i class="bi-phone icon-feature text-gradient d-block mb-3"></i>
                                    <h3 class="font-alt">Flexible Use</h3>
                                    <p class="text-muted mb-0">You can sell products anywhere and anytime</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!-- Feature item-->
                                <div class="text-center">
                                    <i class="bi-people icon-feature text-gradient d-block mb-3"></i>
                                    <h3 class="font-alt">Customer Support</h3>
                                    <p class="text-muted mb-0">
                                        We are always online 24 hours to help you at any time
                                        </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!-- Feature item-->
                                <div class="text-center">
                                    <i class="bi-gift icon-feature text-gradient d-block mb-3"></i>
                                    <h3 class="font-alt">Get Benefit++</h3>
                                    <p class="text-muted mb-0">
                                        Get more benefits from the sales of our products
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!-- Feature item-->
                                <div class="text-center">
                                    <i class="bi-patch-check icon-feature text-gradient d-block mb-3"></i>
                                    <h3 class="font-alt">100% Trusted</h3>
                                    <p class="text-muted mb-0">You can safely withdraw 100% of your sales profits</p>
                                </div>
                            </div>
                        </div>
                </div>
        </div>
    </section>
        <!-- Quote/testimonial aside-->
        <aside class="text-center banner">
            <div class="container px-5">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="h2 fs-1 text-white mb-4">"Just at home, create your personal shop and sell the products we provide, get profits from the number of sales of our products"</div>
                        {{-- <img src="{{ optional($store)->logo ?? asset('images/logo.png') }}" alt="..." style="height: 3rem" /> --}}
                    </div>
                </div>
            </div>
        </aside>
        <!-- App features section-->

        <!-- Basic features section-->
        <section class="bg-light" id="product">
            <div class="container px-5">
                <div class="row gx-5 align-items-center justify-content-center justify-content-lg-between">
                    <div class="col-12 col-md-7">
                        <h2 class="display-4 lh-1 mb-4">We provide various kinds of products in various categories</h2>
                        <p class="lead fw-normal text-muted mb-2">You register as a seller and take the products provided to resellers online and promote your shop or product to get a profit from total sales, login and register as a seller!!</p>
                        @if(Auth::check() && Auth::user()->role == 3)
                        <a href="/join-seller" class="primary-btn text-decoration-none py-3 px-4 mt-4">Join As Seller</a>
                        @else
                        <a href="/login" class="primary-btn text-decoration-none py-3 px-4 mt-4">Login Now</a>
                        @endif
                    </div>
                    <div class="col-sm-8 col-md-5 mt-md-0 mt-5">
                        <div class="px-5 px-sm-0"><img class="img-fluid" src="{{asset('images/product.png')}}" alt="..." /></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- App badge section-->
        <section style="background-color: #ff9c2c" id="download">
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
                    <p><i class="fa-solid fa-envelope"></i> {{ $user->email ?? '' }}</p>
                    <h4 class="font-alt">Telepone</h4>
                    <p><i class="fa-solid fa-phone"></i> {{ $user->phone ?? '' }}</p>
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
                        All rights reserved | Esselexpres
                    </div>
            </div>
        </footer>

        @include('sweetalert::alert')
        @include('includes.penjual.script')
 
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
