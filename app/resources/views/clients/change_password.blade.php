@extends('layouts.clients.master')
@section('css')
<!-- Sidemenu-respoansive-tabs css -->
<link href="{{URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}" rel="stylesheet">
@endsection
@section('content')
		<div class="container-fluid">
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error) 
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			@if (session()->has('Add'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>{{ session()->get('Add') }}</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div> 
			@endif
			@if (session()->has('Update'))
				<div class="alert alert-primary alert-dismissible fade show" role="alert">
					<strong>{{ session()->get('Update') }}</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div> 
			@endif
			@if (session()->has('Delete'))
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>{{ session()->get('delete') }}</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			@endif
			<div class="row no-gutter">
				<!-- The image half -->
				{{-- <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
					<div class="row wd-100p mx-auto text-center">
						<div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
							<img src="{{URL::asset('assets/img/media/reset.png')}}" class="my-auto ht-xl-80p wd-md-100p wd-xl-50p ht-xl-60p mx-auto" alt="logo">
						</div>
					</div>
				</div> --}}
				<!-- The content half -->
				<div class="col-md-6 col-lg-6 col-xl-5 bg-white">
					<div class="login d-flex align-items-center py-2">
						<!-- Demo content-->
						<div class="container p-0">
							<div class="row">
								<div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
									<!--<div class="mb-5 d-flex"> <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="sign-favicon ht-40" alt="logo"></a><h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">Va<span>le</span>x</h1></div>-->
									<div class="main-card-signin d-md-flex">
										<div class="wd-100p">
											<div class="main-signin-header">
												<div class="">
													<h2>مرحبا بك</h2>
													<h4 class="text-right"> (اجباري ) تغيير كلمة المرور</h4>
													<form method="POST" action="{{ route('user.change_password') }}">
														{{ csrf_field() }}
														<input type="hidden" name="email" id="user_email" value="">
														@error('email')
															<span class="invalid-feedback" role="alert">
																<strong>{{ $message }}</strong>
															</span>
														@enderror
														<div class="form-group text-left"> 
															<label>كلمة المرور</label>
															<input class="form-control" placeholder="ادخل كلمة المرور" name="password" id="password" type="password">
															@error('password')
																<span class="invalid-feedback" role="alert">
																	<strong>{{ $message }}</strong>
																</span>
															@enderror
														</div>
														<div class="form-group text-left">
															<label>تأكيد كلمة المرور</label>
															<input class="form-control" placeholder="ادخل تأكيد كلمة المرور" name="password_confirmation" id="password_confirmation" type="password">
															@error('password_confirmation')
																<span class="invalid-feedback" role="alert">
																	<strong>{{ $message }}</strong>
																</span>
															@enderror
														</div>
														<button type="submit" class="btn ripple btn-main-primary btn-block">تغيير كلمة المرور</button>
													</form>
												</div>
											</div>
											<div class="main-signup-footer mg-t-20">
												{{-- <p>Already have an account? <a href="{{ url('/' . $page='signin') }}">Sign In</a></p> --}}
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
<script>
    $(document).ready(function() {
        var UserEmail = "{{$user_data->email}}";

        document.getElementById("user_email").value = UserEmail;

    });
</script>
@endsection