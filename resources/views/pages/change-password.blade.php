<!DOCTYPE html>
<html lang="en">
<head>
	<title>Change Password</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	@php
	use App\Models\Store;
	$store = Store::whereHas('users', function ($query) {
		$query->where('role', 1);
	})->with('users')->first();
	@endphp
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{ optional($store)->logo ?? asset('images/logo.png') }}" style="width: 16px; height: 16px; object-fit: contain;"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('form/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('form/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('form/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('form/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('form/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('form/vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('form/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('form/vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('form/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('form/css/main.css')}}">
<!--===============================================================================================-->
<style>
	.login100-form{
		padding-top: 60px;
	}
</style>
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
	
			<div class="wrap-login100">
				<form action="{{ route('change-password') }}" method="POST" class="login100-form validate-form">
					@csrf
					<div class="logo-form pb-5 text-center">
						<a class="navbar-brand fw-bold" href="/"><img src="{{ optional($store)->logo ?? asset('images/logo.png') }}" style="width: 130px;" alt="Logo"></a>
					</div>
					
					<span class="login100-form-title p-b-43">
						Change Password 
					</span>

					@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
					
					
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: name@gmail.com">
						<input class="input100" type="text" name="email">
						<span class="focus-input100"></span>
						<span class="label-input100">Email</span>
					</div>
					
					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="old_password">
						<span class="focus-input100"></span>
						<span class="label-input100">Old Password</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="password">
						<span class="focus-input100"></span>
						<span class="label-input100">New Password</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="password_confirmation">
						<span class="focus-input100"></span>
						<span class="label-input100">Confirm New Password</span>
					</div>

					<div class="flex-sb-m w-full p-t-3 p-b-32">
						<div>
							<p class="txt1 d-inline">
								Want to come in?
							</p>
							<a href="/register{{ isset($_GET['next']) ? '?next=' . $_GET['next'] : '' }}" class="txt1 text-warning">
								Login Now
							</a>
						</div>
					</div>
			

					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Change Password Now
						</button>
					</div>
				</form>

				<div class="login100-more" style="background-image: url('/form/images/bg-01.png');">
				</div>
			</div>
		</div>
	</div>
	
	@include('sweetalert::alert')
	
<!--===============================================================================================-->
	<script src="{{asset('form/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('form/vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('form/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{asset('form/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('form/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('form/vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{asset('form/vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('form/vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('form/js/main.js')}}"></script>

</body>
</html>