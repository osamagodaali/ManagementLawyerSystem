@extends('layouts.master')
@section('title')
 قائمة انواع القضايا
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
				<h4 class="content-title mb-0 my-auto">الاعدادات </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة انواع القضايا</span>
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
						@can('قائمة انواع القضايا')
							<div class="card mg-b-20">
								<div class="card-header pb-0">
									<div class="d-flex justify-content-between">
										<h4 class="card-title mg-b-0">قائمة انواع القضايا</h4>
										<i class="mdi mdi-dots-horizontal text-gray"></i>
										@can('انشاء نوع قضية')
											<div class="col-sm-6 col-md-4 col-xl-3">
												<a class="btn ripple btn-outline-primary btn-block" data-target="#select2modal" data-toggle="modal" href=""> إضافة نوع قضية جديد</a>
											</div>
										@endcan
									</div>
								</div>
								<!-- Basic modal -->
								<div class="modal" id="select2modal">
									<div class="modal-dialog" role="document">
										<div class="modal-content modal-content-demo">
											<form action="{{ route('casestypes.store') }}" method="post" autocomplete="off">
												{{ csrf_field() }} 
												<div class="modal-header">
													<h6 class="modal-title">إضافة نوع قضية جديد</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
												</div>
												<div class="modal-body">
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-2">
															<label class="form-label mg-b-0">نوع القضية</label>
														</div>
														<div class="col-md-10 mg-t-5 mg-md-t-0">
															<input name="name" class="form-control" type="text">
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button class="btn ripple btn-primary" type="submit"> حفظ</button>
													<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">غلق</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- End Basic modal -->
								<div class="card-body">
									<div class="table-responsive">
										<table id="example" class="table key-buttons text-md-nowrap">
											<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0">الاسم </th>
													<th class="border-bottom-0">التاريخ </th>
													<th class="">العمليات</th>
												</tr>
											</thead>
											<tbody>
												@php
													$i = 0;
												@endphp
												@foreach ($casesTypes as $value)
													@php
														$i++;
													@endphp
													<tr>
														<td>{{$i}}</td>
														<td>{{$value->name }}</td> 
														<td>{{$value->created_at }}</td> 
														<td>
															@can('تعديل نوع القضية')
																<a class="btn btn-sm btn-outline-primary" data-target="#select2modalEdit{{$value->id }}" data-toggle="modal" href="">تعديل</a>
															@endcan
															@can('حذف نوع القضية')
																<button class="btn btn-outline-danger btn-sm"
																	data-toggle="modal"
																	data-casestypeid="{{ $value->id }}"
																	data-target="#delete_casestype">حذف</button>
															@endcan
														</td>
													</tr>
													<!-- Basic modal -->
													<div class="modal" id="select2modalEdit{{$value->id }}">
														<div class="modal-dialog" role="document">
															<div class="modal-content modal-content-demo">
																<form action="{{ route('casestypes.update',$value->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
																	<input type="hidden" name="_method" value="PATCH">
																	{{ csrf_field() }} 
																	<div class="modal-header">
																		<h6 class="modal-title">تعديل نوع القضية</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
																	</div>
																	<div class="modal-body">
																		<div class="row row-xs align-items-center mg-b-20">
																			<div class="col-md-2">
																				<label class="form-label mg-b-0">نوع القضية</label>
																			</div>
																			<div class="col-md-10 mg-t-5 mg-md-t-0">
																				<input name="name" class="form-control" type="text" value="{{$value->name }}">
																			</div>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button class="btn ripple btn-primary" type="submit"> حفظ</button>
																		<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">غلق</button>
																	</div>
																</form>
															</div>
														</div>
													</div>
													<!-- End Basic modal -->
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						@endcan
					</div>
					<!--/div-->
					<div class="modal fade" id="delete_casestype" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
						aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">حذف نوع القضية</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form action="{{ route('casestypes.destroy','index') }}" method="post">
									{{ method_field('delete') }}
									{{ csrf_field() }}
									<div class="modal-body">
										<p class="text-center">
											<h6 style="color:red"> هل انت متاكد من عملية حذف نوع القضية ؟</h6>
										</p>
										<input type="hidden" name="casestypeid" id="casestypeid" value="">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
										<button type="submit" class="btn btn-danger">تاكيد</button>
									</div>
								</form>
							</div>
						</div>
					</div>
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
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<script>
	$('#delete_casestype').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget)
		var casestypeid = button.data('casestypeid')
		var modal = $(this)
		modal.find('.modal-body #casestypeid').val(casestypeid);
	})
</script>
@endsection

