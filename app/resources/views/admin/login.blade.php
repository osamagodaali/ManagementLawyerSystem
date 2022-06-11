@extends('layouts.master3')
@section('title')
تسجيل دخول الموظفين 
@stop
@section('css')
<!-- Sidemenu-respoansive-tabs css -->
<link href="{{URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}" rel="stylesheet">
@endsection
@php
	$setting = App\Models\Setting::find(1);
@endphp
@section('content')
		<div class="container-fluid">
			<div class="row no-gutter">
				<!-- The image half -->
				<div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
					<div class="row wd-100p mx-auto text-center">
						<div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
							<img src="{{URL::asset('app/storage/assets/img/legal/admin_login/'.$setting->login_admin_img_name)}}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
						</div>
					</div>
				</div>
				
				<!-- The content half -->
				<div class="col-md-6 col-lg-6 col-xl-5 bg-white">
					<div class="login d-flex align-items-center py-2">
						<!-- Demo content-->
						<div class="container p-0">
							<div class="row">
								<div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
									<div class="card-sigin">
										<div class="mb-5 d-flex"> <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('app/storage/assets/img/legal/logo/'.$setting->logo_img_name)}}" class="sign-favicon ht-40" alt="logo"></a><h1 class="main-logo1 ml-1 mr-0 my-auto tx-28"> <span>{{ $setting->company_name }}</span></h1></div>
										<div class="card-sigin">
											<div class="main-signup-header">
												<h2>مرحبا بك!</h2>
												<h5 class="font-weight-semibold mb-4">صفحة تسجيل دخول الموظفين   </h5>
												<form method="POST" action="{{ route('admin.login') }}">
                                                    {{ csrf_field() }}
													<div class="form-group">
														<label>البريد الالكتروني</label> <input class="form-control" name="email" :value="old('email')" required autofocus placeholder="ادخل البريد الالكتروني" type="text">
													</div>
													<div class="form-group"> 
														<label>كلمة المرور</label> <input class="form-control" name="password" placeholder="ادخل كلمة المرور" type="password">
													</div><button class="btn btn-main-primary btn-block"> تسجيل دخول</button>	
												</form>
												<div class="main-signin-footer mt-5">
													<p>
														@if (Route::has('admin.password.request'))
															<a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('admin.password.request') }}">
																نسيت كلمة المرور
															</a>
														@endif 
													</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!-- End -->
					</div>
				</div><!-- End -->
			</div>
		</div>
@endsection
@section('js')
@endsection