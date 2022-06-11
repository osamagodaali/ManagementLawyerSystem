@extends('layouts.master')
@section('title')
/ قائمة المصروفات
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
				<h4 class="content-title mb-0 my-auto">الحسابات </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة المصروفات</span>
			</div>
		</div>
		<div class="col-lg-6 col-xl-3 col-md-6 col-12">
			<div class="card bg-primary-gradient text-white ">
				<div class="card-body">
					<div class="row">
						<div class="col-6">
							<div class="icon1 mt-2 text-center">
								<i class="fe fe-credit-card tx-40"></i>
							</div>
						</div>
						<div class="col-6">
							<div class="mt-0 text-center">
								<span class="text-white">اجمالي المصروفات</span>
								<h2 class="text-white mb-0">{{ $all_expenses }} دولار </h2>
							</div>
						</div>
					</div>
				</div>
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
						@can('قائمة المصروفات')
							<div class="card mg-b-20">
								<div class="card-header pb-0">
									<div class="d-flex justify-content-between">
										<h4 class="card-title mg-b-0">قائمة المصروفات</h4>
										<i class="mdi mdi-dots-horizontal text-gray"></i>
										@can('انشاء مصروف')
											<div class="col-sm-6 col-md-4 col-xl-3">
												<a class="btn ripple btn-outline-primary btn-block" data-target="#select2modal" data-toggle="modal" href=""> إضافة مصروف جديد</a>
											</div>
										@endcan
									</div>
								</div>
								<!-- Basic modal -->
								<div class="modal" id="select2modal">
									<div class="modal-dialog" role="document">
										<div class="modal-content modal-content-demo">
											<form action="{{ route('expenses.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
												{{ csrf_field() }} 
												<div class="modal-header">
													<h6 class="modal-title">إضافة مصروف جديد</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
												</div>
												<div class="modal-body">
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-2">
															<label class="form-label mg-b-0"> العميل </label>
														</div>
														<select name="caseid" class="form-control col-md-10 mg-t-5 mg-md-t-0 select2" >
															<option value="0">اختر القضية </option>
																@php
																	$i = 0;
																@endphp
																@foreach ($cases as $case)
																	@php
																		$i++;
																	@endphp
																	<option value="{{ $case->id }}">{{$case->name}} - قضية رقم ( {{$case->case_number}} )</option>
																@endforeach
														</select>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-2">
															<label class="form-label mg-b-0"> مبلغ الاتعاب   </label>
														</div>
														<div class="col-md-10 mg-t-5 mg-md-t-0">
															<input name="value" class="form-control" placeholder=" مبلغ الاتعاب " type="number">
														</div>
													</div>
													<div class="row row-xs align-items-center mg-b-20">
														<div class="col-md-2">
															<label class="form-label mg-b-0">التفاصيل  </label>
														</div>
														<div class="col-md-10 mg-t-5 mg-md-t-0">
															<textarea name="details" class="form-control"></textarea>
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
													<th class="border-bottom-0">العميل </th>
													<th class="border-bottom-0">رقم القضية </th>
													<th class="border-bottom-0">المبلغ </th>
													<th class="border-bottom-0">التفاصيل </th>
													<th class="border-bottom-0">الدفع بواسطة </th>
													<th class="border-bottom-0">التاريخ </th>
													<th class="">العمليات</th>
												</tr>
											</thead>
											<tbody>
												@php
													$i = 0;
												@endphp
												@foreach ($expenses as $value)
													@php
														$i++;
													@endphp
													<tr>
														<td>{{$i}}</td>
														<td>{{$value->name }}</td> 
														<td>{{$value->case_number }}</td> 
														<td>{{$value->value }}</td> 
														<td>{{$value->details }}</td> 
														<td>{{$value->employee_name }}</td> 
														<td>{{$value->created_at }}</td> 
														<td>
															@can('تعديل مصروف')
																<a class="btn btn-sm btn-outline-primary" data-target="#select2modalEdit{{$value->id }}" data-toggle="modal" href="">تعديل</a>
															@endcan
															@can('حذف مصروف')
																<button class="btn btn-outline-danger btn-sm"
																	data-toggle="modal"
																	data-expenseid="{{ $value->id }}"
																	data-target="#delete_expense">حذف</button>
															@endcan
														</td>
													</tr>
													<!-- Basic modal -->
													<div class="modal" id="select2modalEdit{{$value->id }}">
														<div class="modal-dialog" role="document">
															<div class="modal-content modal-content-demo">
																<form action="{{ route('expenses.update',$value->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
																	<input type="hidden" name="_method" value="PATCH">
																	{{ csrf_field() }} 
																	<div class="modal-header">
																		<h6 class="modal-title">تعديل مصروف جديد</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
																	</div>
																	<div class="modal-body">
																		<div class="row row-xs align-items-center mg-b-20">
																			<div class="col-md-2">
																				<label class="form-label mg-b-0"> العميل </label>
																			</div>
																			<select name="caseid" class="form-control col-md-10 mg-t-5 mg-md-t-0 select2" >
																				<option value="0">اختر القضية </option>
																					@php
																						$i = 0;
																					@endphp
																					@foreach ($cases as $case)
																						@php
																							$i++;
																						@endphp
																						<option value="{{ $case->id }}" {{ ($case->id == $value->caseid ) ? 'selected' : '' }} >{{$case->name}} - قضية رقم ( {{$case->case_number}} )</option>
																					@endforeach
																			</select>
																		</div>
																		<div class="row row-xs align-items-center mg-b-20">
																			<div class="col-md-2">
																				<label class="form-label mg-b-0"> مبلغ الاتعاب   </label>
																			</div>
																			<div class="col-md-10 mg-t-5 mg-md-t-0">
																				<input name="value" class="form-control" placeholder=" مبلغ الاتعاب " type="number" value="{{$value->value }}">
																			</div>
																		</div>
																		<div class="row row-xs align-items-center mg-b-20">
																			<div class="col-md-2">
																				<label class="form-label mg-b-0">التفاصيل  </label>
																			</div>
																			<div class="col-md-10 mg-t-5 mg-md-t-0">
																				<textarea name="details" class="form-control">{{$value->details }}</textarea>
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
					<div class="modal fade" id="delete_expense" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
						aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">حذف المصروف</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form action="{{ route('expenses.destroy', 'index') }}" method="post">
									<input type="hidden" name="_method" value="DELETE">
									{{ csrf_field() }}
									<div class="modal-body">
										<p class="text-center">
											<h6 style="color:red"> هل انت متاكد من عملية حذف المصروف ؟</h6>
										</p>
										<input type="hidden" name="expenseid" id="expenseid" value="">
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
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<script>
	$('#delete_expense').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget)
		var expenseid = button.data('expenseid')
		var modal = $(this)
		modal.find('.modal-body #expenseid').val(expenseid);
	})
</script>
@endsection

