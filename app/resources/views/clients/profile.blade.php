@extends('layouts.clients.master')
@section('css')
<!-- Internal Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto"> البروفايل</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل البروفايل</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb --> 
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
					<!-- Col -->
					<div class="col-lg-12">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error) 
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session()->has('success'))
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <strong>{{ session()->get('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ session()->get('error') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
						<div class="card">
                            <form class="form-horizontal" action="{{route('user.update_profile')}}" method="post" data-parsley-validate="" >
                                {{ csrf_field() }}
                                <div class="pd-30 pd-sm-40 bg-gray-200">
                                    <div class="col-6">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">الاسم بالكامل : <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="name" id="name" required="" type="text" value="{{ $user->name }}" >
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">البريد الالكتروني : <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="email" id="email" required="" type="email" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">رقم التليفون : <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="mobile" id="mobile" required="" type="number" maxlength="11" minlength="11" value="{{ $user->mobile }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">الرقم القومي : <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="nationalid" id="nationalid" required="" type="number" maxlength="14" minlength="14" value="{{ $user->nationalid }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">address : <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="address" id="address" required="" type="text" value="{{ $user->address }}" >
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-6">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">كلمة المرورالقديمة : </label>
                                            <input class="form-control" name="old_password"   type="password" >
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">كلمة المرور : </label>
                                            <input class="form-control" name="password"   type="password" >
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">تأكيد كلمة المرور : </label>
                                            <input class="form-control" name="password_confirmation"   type="password" >
                                        </div>
                                    </div>
                                    <br>
                                    <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تحديث  </button>
                                </div>
                            </form> 
						</div>
					</div>
					<!-- /Col -->
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
        
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/select2.js')}}"></script>
<!--Internal  Parsley.min js -->
<script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
<!-- Internal Form-validation js -->
<script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
<script>
    $(document).ready(function() {
        var Name        = "{{$user->name}}";
        var Email       = "{{$user->email}}";
        var Mobile      = "{{$user->mobile}}";
        var NationalId  = "{{$user->nationalid}}";
        var Address     = "{{$user->address}}";

        document.getElementById("name").value       = Name;
        document.getElementById("email").value      = Email;
        document.getElementById("mobile").value     = Mobile;
        document.getElementById("nationalid").value = NationalId;
        document.getElementById("address").value    = Address;
    });
</script>
@endsection