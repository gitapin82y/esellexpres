
        <!-- Header-->
        <header id="header" class="header">
            <div class="top-left h-100"  style="max-height: 60px">
                <div class="navbar-header "  style="max-height: 60px">
                    @php
                    use App\Models\Store;
                    $store = Store::whereHas('users', function ($query) {
                        $query->where('role', 1);
                    })->with('users')->first();
                @endphp
                    <a class="navbar-brand" href="./"><img src="{{ optional($store)->logo ? asset($store->logo) : asset('images/logo.png') }}" style="max-height: 40px" alt="Logo"></a>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">
                    <div class="header-left">
                        @if (Auth::check() && Auth::user()->role == 1)
                        <div class="dropdown">
                            Balance <small>(Total Income)</small> : 
                            <span style="color:#e7ab3c">${{Auth::user()->balance}}</span>
                        </div>
                        @else
                        <div class="dropdown">
                            <a href="#" class="text-dark" data-toggle="modal" data-target="#topUpModal">
                            Balance : 
                            <span style="color:#e7ab3c">${{Auth::user()->balance}}</span>
                                <i class="fa fa-plus-circle" style="color: #e7ab3c;"></i>
                            </a>
                        </div>
                        @endif
                        {{-- <div class="dropdown for-message">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="message" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-envelope"></i>
                                <span class="count bg-primary">4</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="message">
                                <p class="red">You have 4 Mails</p>
                                <a class="dropdown-item media" href="#">
                                    <div class="message media-body">
                                        <span class="name float-left">Jonathan Smith</span>
                                        <span class="time float-right">Just now</span>
                                        <p>Hello, this is an example msg</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <div class="message media-body">
                                        <span class="name float-left">Jack Sanders</span>
                                        <span class="time float-right">5 minutes ago</span>
                                        <p>Lorem ipsum dolor sit amet, consectetur</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                    <div class="message media-body">
                                        <span class="name float-left">Cheryl Wheeler</span>
                                        <span class="time float-right">10 minutes ago</span>
                                        <p>Hello, this is an example msg</p>
                                    </div>
                                </a>
                                <a class="dropdown-item media" href="#">
                                  
                                    <div class="message media-body">
                                        <span class="name float-left">Rachel Santos</span>
                                        <span class="time float-right">15 minutes ago</span>
                                        <p>Lorem ipsum dolor sit amet, consectetur</p>
                                    </div>
                                </a>
                            </div>
                        </div> --}}
                    </div>

                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{Auth::user()->name}} &nbsp;
                            <img class="user-avatar rounded-circle" src="{{asset('images/admin.jpg')}}" alt="User Avatar">
                        </a>

                        <div class="user-menu dropdown-menu">
                            {{-- <a class="nav-link" href="#"><i class="fa fa-user"></i>My Profile</a> --}}
                            <a class="nav-link" href="{{route('logoutApp')}}"><i class="fa fa-power-off"></i>Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header><!-- /header -->

        <div id="topUpModal" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xs">
          
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header bg-gradient-info">
                  <h4 class="modal-title d-inline">Top Up Saldo</h4>
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
                    <button class="btn btn-success" id="simpan" type="submit">Send Request</button>
        
                 </form>
                </div>
                </div>
          
            </div>
        </div>
        <!-- Header-->