@extends('layouts.master2')
@section('css')
<!-- Sidemenu-respoansive-tabs css -->
<link href="{{URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}" rel="stylesheet">
@endsection
@section('content')
		<div class="container-fluid">
			<div class="row no-gutter">
				<!-- The image half -->
				<div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
					<div class="row wd-100p mx-auto text-center">
						<div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
							<img src="{{URL::asset('assets/img/media/login.png')}}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
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
										<div class="mb-5 d-flex"> <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="sign-favicon ht-40" alt="logo"></a><h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">Va<span>le</span>x</h1></div>
										<div class="main-signup-header">
											<h2 class="text-primary">تسجيل جديد</h2>
											<form method="POST" action="{{ route('register') }}">
                                                {{ csrf_field() }}
												<div class="form-group">
													<label>الاسم الاول &amp; الاسم الاخير</label> <input class="form-control" name="name" placeholder="ادخل الاسم الاول والاخير" type="text">
												</div>
												<div class="form-group">
													<label>رقم الهاتف</label> <input class="form-control" name="mobile" placeholder="ادخل رقم الهاتف" type="number">
												</div>
                                                <div class="form-group">
													<label>البريد الالكتروني</label> <input class="form-control" name="email" placeholder="ادخل البريد الالكتروني" type="email">
												</div>
												<div class="form-group">
													<label>كلمة المرور</label> <input class="form-control" name="password" placeholder="ادخل كلمة المرور" type="password">
												</div>
                                                <div class="form-group">
													<label>تأكيد كلمة المرور</label> <input class="form-control" name="password_confirmation" placeholder="ادخل تأكيد كلمةالمرور" type="password">
												</div>
                                                
                                                <button class="btn btn-main-primary btn-block">انشاء الاحساب</button>
											</form>
											<div class="main-signup-footer mt-5">
												<p>لديك حساب بالفعل ؟ <a href="{{ url('/' . $page='auth.login') }}">تسجيل دخول</a></p>
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