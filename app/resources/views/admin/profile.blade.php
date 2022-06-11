@extends('layouts.master')
@section('title')
البروفايل
@stop
@section('css')

@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">البروفايل </h4>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
        <!-- row -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
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
                @can('الاعدادات')
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.update_profile') }}" method="post" data-parsley-validate="" enctype="multipart/form-data" autocomplete="off">
                                {{ csrf_field() }} 
                                <input type="hidden" name="admin_id" value="{{ $profiledata->id }}" />
                                <div class="pd-30 pd-sm-40 bg-gray-200">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">الاسم بالكامل : <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="name"  required="" type="text" value="{{ $profiledata->name }}" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">رقم التليفون : <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="mobile"  required="" type="number" maxlength="11" minlength="11" value="{{ $profiledata->mobile }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">البريد الالكتروني : <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="email"  required="" type="email" value="{{ $profiledata->email }}">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">كلمة المرورالقديمة : </label>
                                            <input class="form-control" name="old_password"   type="password" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">كلمة المرور : </label>
                                            <input class="form-control" name="password"   type="password" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">تأكيد كلمة المرور : </label>
                                            <input class="form-control" name="password_confirmation"   type="password" >
                                        </div>
                                    </div>
                                    <br>
                                    <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تحديث </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
        <!-- /row -->
        </div>
    </div>
    <!-- Container closed -->
@endsection
@section('js')
<!--Internal  Parsley.min js -->
<script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
<!-- Internal Form-validation js -->
<script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
@endsection
