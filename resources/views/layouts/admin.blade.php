<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Esellexpress | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
    use App\Models\Store;
    $store = Store::whereHas('users', function ($query) {
        $query->where('role', 1);
    })->with('users')->first();
    @endphp
    <link rel="icon" href="{{ optional($store)->logo ?? asset('images/logo.png') }}" style="object-fit: contain;">
    
    @include('includes.style')
    @stack('after-style');
</head>

<body>
    <!-- Left Panel -->
    @include('includes.sidebar')
    <!-- /#left-panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        @include('includes.navbar')
        <!-- /#header -->
        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            @yield('content')
            <!-- .animated -->
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
    </div>
    @include('sweetalert::alert')
    <!-- /#right-panel -->
    @stack('before-script');
    @include('includes.script')
    @stack('after-script');
</body>
</html>
