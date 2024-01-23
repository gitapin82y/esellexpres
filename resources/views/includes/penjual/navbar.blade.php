   <!-- Header Section Begin -->
   <header class="header-section">
    <div class="header-top">
        <div class="container">
            @php
                use App\Models\Store;
                $store = Store::with('users')->where('slug',request()->segment(1))->first();
            @endphp
            <div class="ht-left">
                <div class="mail-service">
                    <i class=" fa fa-envelope"></i> {{$store->users->email}}
                </div>
                <div class="phone-service">
                    <i class=" fa fa-phone"></i> {{$store->users->phone}}
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="inner-header">
            <div class="row py-4 py-md-0 justify-content-between">
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="logo p-0">
                        <a href="/{{request()->segment(1)}}">
                            <img src="{{asset($store->logo)}}" style="width: 120px;" />
                        </a>
                    </div>
                </div>
               
                <div class="col-lg-10 align-self-center text-right justify-content-center justify-content-md-end d-flex col-md-8 col-12">
                    
                    <ul class="nav-right mt-2">
                        @if (Auth::check())
                        <li class="mr-3 mr-md-2">
                            <a href="#" class="text-dark" data-toggle="modal" data-target="#topUpModal">
                            <span style="color:#e7ab3c;font-size:24px;">${{Auth::user()->balance}}</span>
                                <i class="icon_plus_alt2" style="color: #e7ab3c;"></i>
                            </a>
                        </li>
                        @endif
                        <li class="cart-icon mr-3 mr-md-2">
                            <a href="/{{request()->segment(1).'/shopping-cart'}}">
                                <div class="d-none d-sm-block">
                                    Shopping Cart &nbsp;
                                </div>
                                <div class="d-block d-sm-none">
                                    Cart &nbsp;
                                </div>
                                <a href="#">
                                    <i class="icon_bag_alt"></i>
                                    <span id="cart-count">0</span>
                                </a>
                                <div class="cart-hover" style="margin-top:-25px;">
                                    <div class="select-items">
                                        <table>
                                            <tbody id="cart-list-container">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="select-total">
                                        <span>total:</span>
                                        <h5 id="total-price"></h5>
                                    </div>
                                    <div class="select-button">
                                        <a href="/{{request()->segment(1).'/shopping-cart'}}" class="primary-btn checkout-btn py-3 px-4">CHECKOUT</a>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @if (Auth::check())
                        <li class="cart-icon">
                            <a href="/{{request()->segment(1)}}/status-produk">
                                {{Auth::user()->name}}
                                &nbsp;
                               <a href="#" style="margin-bottom: -12px">
                                   <img class="user-avatar" src="{{asset(Auth::user()->avatar)}}" alt="User Avatar">
                               </a>
                               <div class="cart-hover" style="width: 200px; margin-right:40px;margin-top:-25px;">
                                   <div class="select-items">
                                       {{-- <a href="/{{request()->segment(1)}}/profile" class="mb-3">Profile</a> --}}
                                       {{-- <br> --}}
                                       @if (Auth::user()->role != 3)
                                       <a href="/dashboard" class="mb-2" style="font-size:17px;">Dashboard</a>
                                        <br>
                                       @endif
                                       <a href="/{{request()->segment(1)}}/information-profile" class="mb-2" style="font-size:17px;">Profile</a><br>
                                       <a href="/{{request()->segment(1)}}/status-produk" class="mb-2" style="font-size:17px;">My Order</a>
                                       <br>
                                       <a href="#" class="text-dark mb-2" data-toggle="modal" data-target="#topUpModal" style="font-size:17px;">Top Up</a>
                                       <br>
                                       <a href="#" class="text-dark mb-2" data-toggle="modal" data-target="#withdrawModal" style="font-size:17px;">Withdraw</a>
                                       <br>
                                       <a href="/reset-password" class="mb-2" style="font-size:17px;">Reset Password</a>
                                       <br>
                                       <a href="{{ url('/logout?next=' . url()->full()) }}" style="font-size:17px;">Logout</a>
                                   </div>
                               </div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<div id="topUpModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-gradient-info">
          <h4 class="modal-title d-inline">Request Top Up</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
         <form action="{{ route('topup-balance.index') }}" method="POST"  id="form-modal" enctype="multipart/form-data" >
                @csrf

                Complete your payment and transfer to : 
                        <div class="px-3 pt-2 my-2" style="background-color: #eff6ff">
                            @php
                                $items = DB::table('bank_accounts')->get();
                            @endphp
                            @foreach ($items as $item)
                            <h5 style="font-weight:bold;margin-top:10px;"> {{ $item->type_payment }}</h5> 
                            <div class="row col-12" style="margin-bottom:10px;">
                                <p class="account-number text-primary" id="accountNumber{{ $loop->index }}">{{ $item->account_number }}</p>
                                &nbsp;
                            <a class="copy-button" style="cursor: pointer;" data-clipboard-target="#accountNumber{{ $loop->index }}">(Click for copy)</a>
                            </div>
                            @endforeach
                        </div>

                  <label for="total">
                      Total Top Up<span style="color:red;">*</span>
                  </label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                <input type="text" class="form-control h-auto form-control-sm inputtext" id="total" name="total" placeholder="0.00" oninput="handleInput(this)" required>
            </div>
            <div class="form-group">
                <label for="proof">
                    Proof<span style="color:red;">*</span>
                </label>
                <input type="file" class="form-control h-auto form-control-sm inputtext" id="proof" name="proof" required>
            </div>
            <div class="form-group">
                <label for="message">
                    Message
                </label>
                <input type="text" class="form-control form-control-sm inputtext" id="message" name="message">
            </div>
            <button class="btn btn-main w-100" id="simpan" type="submit">Send Request</button>

         </form>
        </div>
        </div>
  
    </div>
</div>

<!-- Modal -->
<div id="withdrawModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header bg-gradient-info">
          <h4 class="modal-title d-inline">Request Withdraw</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
         <form action="{{ route('withdraw-balance.index') }}" method="POST"  id="form-modal" enctype="multipart/form-data" >
                @csrf
            <div class="form-group">
                <label for="bank_account">
                    Bank Account<span style="color:red;">*</span>
                </label>
                <input type="text" class="form-control form-control-sm inputtext" required id="bank_account" name="bank_account">
            </div>
            <div class="form-group">
                <label for="number">
                    Number<span style="color:red;">*</span>
                </label>
                <input type="number" class="form-control form-control-sm inputtext" required id="number" name="number">
            </div>
            <label for="total">
                Total Withdraw<span style="color:red;">*</span>
            </label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                    </div>
                <input type="text" class="form-control h-auto form-control-sm inputtext" id="total" name="total" placeholder="0.00" oninput="handleInput(this)" required>
            </div>
            {{-- <div class="form-group">
                <label for="proof">
                    Proof<span style="color:red;">*</span>
                </label>
                <input type="file" class="form-control form-control-sm inputtext" id="proof" name="proof" required>
            </div> --}}
            <div class="form-group">
                <label for="message">
                    Message
                </label>
                <input type="text" class="form-control form-control-sm inputtext" id="message" name="message">
            </div>
            <button class="btn btn-main w-100" id="simpan" type="submit">Send Request</button>

         </form>
        </div>
        </div>
  
    </div>
</div>
<!-- Header End -->