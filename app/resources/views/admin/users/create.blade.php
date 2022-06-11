@extends('layouts.master')
@section('title')
إضافة عميل جديد
@stop
@section('css')
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">العملاء</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/  إضافة عميل جديد</span>
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
                @can('انشاء عميل')
                    <div class="card">
                        <div class="card-body">
                            
                            <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data" data-parsley-validate=""
                            autocomplete="off">
                                {{ csrf_field() }} 
                                <div class="pd-30 pd-sm-40 bg-gray-200">
                                    <div class="main-content-label mg-b-5">
                                        إضافة عميل  جديد
                                    </div>
                                    <br>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">الاسم بالكامل: <span class="tx-danger">*</span></label>
                                            <input class="form-control" name="name"  required="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">البريد الالكتروني: </label>
                                            <input class="form-control" name="email"  type="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">رقم الهاتف: </label>
                                            <input class="form-control" name="mobile"  type="number" maxlength="20"  >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">رقم الهوية: </label>
                                            <input class="form-control" name="nationalid"   type="number" maxlength="20" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">العنوان : </label>
                                            <input class="form-control" name="address"   type="text" maxlength="255" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">النوع : </label>
                                            <select name="gender" class="form-control" >
                                                <option value="1">ذكر </option>
                                                <option value="2">أنثى </option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تسجيل جديد</button>
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
