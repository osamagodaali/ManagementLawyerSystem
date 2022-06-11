@extends('layouts.master')
@section('title')
قائمة الادوار
@stop
@section('css')
	<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
	<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
	<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الادوار </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الادوار</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
                
@endsection
@section('content')
				<!-- row -->
				<div class="row">
                    <!--div-->
					<div class="col-xl-12">
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
						@can('قائمة الادوار')
							<div class="card mg-b-20">
								<div class="card-header pb-0">
									<div class="d-flex justify-content-between">
										<h4 class="card-title mg-b-0">قائمة الادوار</h4>
										<i class="mdi mdi-dots-horizontal text-gray"></i>
										@can('انشاء دور')
											<div class="col-sm-6 col-md-4 col-xl-3">
												<a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale"
													href="{{ route('roles.create') }}">إضافة دور جديد</a>
											</div>
										@endcan
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="example" class="table key-buttons text-md-nowrap">
											<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0">الاسم </th>
													<th class="">العمليات</th>
												</tr>
											</thead>
											<tbody>
												@php
													$i = 0;
												@endphp
												@foreach ($data as $key => $role)
													@php
														$i++;
													@endphp
													<tr>
														<td>{{$i}}</td>
														<td>{{$role->name }}</td> 
														<td>
															@can('قائمة الادوار')
																<a class="btn btn-sm btn-success" href="{{ route('roles.show',$role->id) }}">مشاهدة</a>
															@endcan
															@can('تعديل دور')
																<a class="btn btn-sm btn-primary" href="{{ route('roles.edit',$role->id) }}">تعديل</a>
															@endcan
															@can('حذف دور')
																{!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
																	{!! Form::submit('حذف', ['class' => 'btn btn-sm btn-danger']) !!}
																{!! Form::close() !!} 
															@endcan
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						@endcan
					</div>
					<!--/div-->
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
	<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
	<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
@endsection

