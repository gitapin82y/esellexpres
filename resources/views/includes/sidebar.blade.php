 <!-- Left Panel -->
 <aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="{{Request::is('dashboard') ? 'active' : '' }}">
                    <a href="/dashboard"><i class
                        ="menu-icon fa fa-bar-chart"></i>Dashboard </a>
                </li>
                <?php
                use App\Models\BadgeSidebar;
                $incomingOrders = BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'Incoming Orders')->first();
                $sellerCandidates = BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'Seller Candidates')->first();
                $sellerRequest = BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'Seller Request')->first();
                $withdraw = BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'withdraw')->first();
                $topup = BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'topup')->first();
                $withdrawTotal = $withdraw ? $withdraw->total : 0;
                $topupTotal = $topup ? $topup->total : 0;
                $balanceRequest = $withdrawTotal + $topupTotal;
                ?>
                @if(Auth::user()->role == 1)
      
                <li class="{{Request::is('ecommerce') ? 'active' : '' }}">
                    <a href="{{route('ecommerce.index')}}"><i class
                        ="menu-icon fa fa-home"></i>E-commerce</a>
                </li>

                <li class="{{Request::is('delivery-service') ? 'active' : '' }}">
                    <a href="{{route('delivery-service.index')}}"> <i class="menu-icon fa fa-truck"></i>Delivery Service</a>
                    </li>

                <li class="{{Request::is('bank-account') ? 'active' : '' }}">
                    <a href="{{route('bank-account.index')}}"> <i class="menu-icon fa fa-credit-card"></i>Bank Account</a>
                </li>

                <li class="{{Request::is('users') ? 'active' : '' }}">
                    <a href="{{route('users.index')}}"> <i class="menu-icon fa fa-user"></i>All Users</a>
                </li>

                <li class="menu-title">Product</li><!-- /.menu-title -->
                <li class="{{Request::is('products') || Request::is('products/*') ? 'active' : '' }}">
                    <a href="{{route('products.index')}}"> <i class="menu-icon fa fa-shopping-cart"></i>Product List</a>
                </li>

                <li class="{{Request::is('category') ? 'active' : '' }}">
                    <a href="{{route('category.index')}}"> <i class="menu-icon fa fa-list"></i>Product Categories</a>
                </li>
           

                <li class="menu-title">Seller</li><!-- /.menu-title -->
                <li class="{{Request::is('kandidat-penjual') ? 'active' : '' }}">
                    <a href="{{route('kandidat-penjual.index')}}"> <i class="menu-icon fa fa-user-plus"></i>Seller Candidates
                        {!! $sellerCandidates && $sellerCandidates->total > 0 ? '<div class="badge"><span>' . $sellerCandidates->total . '</span></div>' : '' !!}
                </a>
                </li>
                <li class="{{Request::is('list-penjual') ? 'active' : '' }}">
                <a href="{{route('list-penjual.index')}}"> <i class="menu-icon fa fa-users"></i>Seller List
                </a>
                </li>
                <li class="{{Request::is('request-penjual') ? 'active' : '' }}">
                    <a href="{{route('request-penjual.index')}}"> <i class="menu-icon fa fa-send"></i>Seller Request{!! $sellerRequest && $sellerRequest->total > 0 ? '<div class="badge"><span>' . $sellerRequest->total . '</span></div>' : '' !!}</a>
                    
                    </li>
         

                <li class="menu-title">Transaction</li><!-- /.menu-title -->
                <li class="{{Request::is('transaction') ? 'active' : '' }}">
                    <a href="{{route('transaction.index')}}"> <i class="menu-icon fa fa-cart-plus"></i>Incoming Orders{!! $incomingOrders && $incomingOrders->total > 0 ? '<div class="badge"><span>' . $incomingOrders->total . '</span></div>' : '' !!}</a>
                    
                    </li>
                <li class="{{Request::is('topup-request') ? 'active' : '' }}">
                <a href="{{route('topup-request.index')}}"> <i class="menu-icon fa fa-money"></i>Balance Request{!! $balanceRequest > 0 ? '<div class="badge"><span>' . $balanceRequest . '</span></div>' : '' !!}</a>
                
                </li>
            
                @endif
                
                @if(Auth::user()->role == 2)

                <li class="{{Request::is('stores') ? 'active' : '' }}">
                    <a href="{{route('stores.index')}}"><i class
                        ="menu-icon fa fa-home"></i>Store Information</a>
                </li>

                <li class="menu-title">Product</li><!-- /.menu-title -->
                <li class="{{Request::is('products') || Request::is('products/*') ? 'active' : '' }}">
                    <a href="{{route('products.index')}}"> <i class="menu-icon fa fa-user"></i>Reseller Product</a>
                </li>

                <li class="{{Request::is('products-list') || Request::is('products-list/*') ? 'active' : '' }}">
                    <a href="{{route('products-list.index')}}"> <i class="menu-icon fa fa-shopping-cart"></i>Product List</a>
                </li>

                <li class="menu-title">Transaction</li><!-- /.menu-title -->
                    <li class="{{Request::is('transaction') ? 'active' : '' }}">
                    <a href="{{route('transaction.index')}}"> <i class="menu-icon fa fa-cart-plus"></i>Incoming Orders   {!! $incomingOrders && $incomingOrders->total > 0 ? '<div class="badge"><span>' . $incomingOrders->total . '</span></div>' : ''!!}</a>
                 
                    </li>
                    <li class="{{Request::is('topup-balance') ? 'active' : '' }}">
                        <a href="{{route('topup-balance.index')}}"><i class
                            ="menu-icon fa fa-credit-card"></i>Top Up Balance</a>
                    </li>
                    <li class="{{Request::is('withdraw-balance') ? 'active' : '' }}">
                        <a href="{{route('withdraw-balance.index')}}"><i class
                            ="menu-icon fa fa-credit-card-alt"></i>Withdraw Balance</a>
                    </li>
                @endif


            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>