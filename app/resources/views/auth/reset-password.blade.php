@extends('layouts.master3')
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
							<img src="{{URL::asset('app/storage/assets/img/legal/user_login/'.$setting->login_user_img_name)}}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
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
											    @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                @if (session()->has('status'))
                                                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                        <strong>{{ session()->get('status') }}</strong>
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div> 
                                                @endif
												<h2>مرحبا بك !</h2>
												<h5 class="font-weight-semibold mb-4"> صفحة اعادة ادخال كلمة المرور</h5>
												<form method="POST" action="{{ route('password.update') }}">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
													<div class="form-group">
														<label>البريد الالكتروني</label> <input class="form-control" name="email" :value="old('email')" required autofocus placeholder="ادخل البريد الالكتروني" type="text" required autofocus>
													</div>
													<div class="form-group">
														<label>كلمة المرور </label> <input class="form-control" name="password"  placeholder="ادخل كلمة المرور " type="password" required>
													</div>
													<div class="form-group">
														<label>تأكيد كلمة المرور </label> <input class="form-control" name="password_confirmation" placeholder="ادخل تأكيد كلمة المرور " type="password" required>
													</div>
													<button class="btn btn-main-primary btn-block"> ارسال </button>	
												</form>
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