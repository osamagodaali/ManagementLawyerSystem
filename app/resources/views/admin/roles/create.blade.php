@extends('layouts.master')
@section('title')
انشاء دور جديد
@stop
@section('css')
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الادوار</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ انشاء دور جديد</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
@can('انشاء دور')
            {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
				<!-- row -->
				<div class="row">
					<div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
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
								<div class="main-content-label mg-b-5">
									انشاء دور جديد
								</div>
								<p class="mg-b-20">ادخل اسم الدور المطلوب اضافته</p>
								<form class="needs-validation was-validated">
									<div class="row row-sm">
										<div class="col-lg-6">
											<div class="form-group has-success mg-b-0">
                                                {!! Form::text('name', null, array('placeholder' => 'ادخل اسم الدور المطلوب اضافته','class' => 'form-control' , 'required')) !!}
												
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!--/div-->

					<!--div-->
					<div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
						<div class="card">
							<div class="card-body">
								<div class="main-content-label mg-b-5">
									اختر الصلاحيات المطلوبة للدور
								</div>
                                <div class="row">
                                    @foreach($permission as $value)
                                        <div class="col-lg-3">
                                            <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                            {{ $value->name }}</label>
                                        </div>
                                    @endforeach 
								</div>
								<button type="submit" class="btn btn-primary">حفظ</button>
							</div>
						</div>
					</div>
                    
					<!--/div-->
				</div>
				<!-- row closed -->
			<!-- Container closed -->
            {!! Form::close() !!}
			@endcan
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal form-elements js -->
<script src="{{URL::asset('assets/js/form-elements.js')}}"></script>
@endsection