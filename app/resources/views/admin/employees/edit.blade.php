@extends('layouts.master')
@section('title')
تعديل بيانات الموظف
@stop
@section('css')
<!-- Internal Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الموظفين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/  تعديل بيانات الموظف</span>
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
                @can('تعديل موظف')
                    <div class="card">
                        <div class="card-body">
                            
                            {!! Form::model($employee, ['route' => ['employees.update', $employee->id], 'method'=>'PATCH', 'data-parsley-validate' ]) !!}
                                <div class="pd-30 pd-sm-40 bg-gray-200">
                                    <div class="main-content-label mg-b-5">
                                        تعديل بيانات الموظف
                                    </div>
                                    <br>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">الاسم بالكامل: <span class="tx-danger">*</span></label>
                                            {!! Form::text('name', null, array('class' => 'form-control' , 'value'=>'{{ $employee->name }}' ,'required' )) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">البريد الالكتروني: <span class="tx-danger">*</span></label>
                                            {!! Form::email('email', null, array('class' => 'form-control' , 'value'=>'{{ $employee->email }}' ,'required')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">رقم الهاتف: <span class="tx-danger">*</span></label>
                                            {!! Form::number('mobile', null, array('class' => 'form-control' , 'value'=>'{{ $employee->mobile }}','required','maxlength'=>'20' )) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">مشاهده القضايا: <span class="tx-danger">*</span></label>
                                            <select name="cases_availabe" class="form-control " required="" >
                                                <option value="0" {{ $employee->cases_availabe == 0 ? 'selected' : '' }} >القضايا الخاصه فقط </option>
                                                <option value="1" {{ $employee->cases_availabe == 1 ? 'selected' : '' }} >كل القضايا </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">اختيار الادوار: <span class="tx-danger">*</span></label>
                                            <select name="roles[]" class="form-control select2" multiple="multiple" required="" >
                                                <option value="0">اختر المحامي </option>
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($roles as $role)
                                                    @php
                                                        $i++;
                                                    @endphp
                                                    <option value="{{ $role->id }}" {{  in_array($role->id , $followsBy ) ? 'selected' : '' }} >{{$role->name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تحديث </button>
                                </div>
                            {!! Form::close() !!}
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
<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js')}}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
<!-- Ionicons js -->
<script src="{{URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>
<!-- Internal form-elements js -->
<script src="{{URL::asset('assets/js/form-elements.js')}}"></script>
<!--Internal  Parsley.min js -->
<script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
<!-- Internal Form-validation js -->
<script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
@endsection
