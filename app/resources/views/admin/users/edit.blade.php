@extends('layouts.master')
@section('title')
تعديل بيانات العميل
@stop
@section('css')
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">العملاء</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل بيانات العميل</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
            @can('تعديل عميل')
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
                        <div class="card">
                            <div class="card-body">
                                
                                {!! Form::model($users, ['route' => ['users.update', $users->id], 'method'=>'PATCH', 'data-parsley-validate']) !!}
                                    <div class="pd-30 pd-sm-40 bg-gray-200">
                                        <div class="main-content-label mg-b-5">
                                            تعديل بيانات العميل
                                        </div>
                                        <br>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group mg-b-0">
                                                <label class="form-label">الاسم بالكامل: <span class="tx-danger">*</span></label>
                                                {!! Form::text('name', null, array('class' => 'form-control' , 'value'=>'{{ $users->name }}' ,'required' )) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group mg-b-0">
                                                <label class="form-label">البريد الالكتروني: </label>
                                                {!! Form::email('email', null, array('class' => 'form-control' , 'value'=>'{{ $users->email }}' )) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group mg-b-0">
                                                <label class="form-label">رقم الهاتف: </label>
                                                {!! Form::number('mobile', null, array('class' => 'form-control' , 'value'=>'{{ $users->mobile }}','maxlength'=>'20' )) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group mg-b-0">
                                                <label class="form-label">رقم الهوية: </label>
                                                {!! Form::number('nationalid', null, array('class' => 'form-control' , 'value'=>'{{ $users->nationalid }}','maxlength'=>'20' )) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group mg-b-0">
                                                <label class="form-label">العنوان : </label>
                                                {!! Form::text('address', null, array('class' => 'form-control' , 'value'=>'{{ $users->address }}','maxlength'=>'255' )) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group mg-b-0">
                                                <label class="form-label">النوع : </label>
                                                <select name="gender" class="form-control" >
                                                    <option value="1" {{ $users->gender == 1 ? 'selected' : '' }} >ذكر </option>
                                                    <option value="2" {{ $users->gender == 2 ? 'selected' : '' }} >أنثى </option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تحديث  </button>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /row -->
            @endcan
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
