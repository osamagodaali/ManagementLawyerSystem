@extends('layouts.master')
@section('title')
قائمة العملاء
@stop
@section('css')
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
{{-- <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet"> --}}
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">العملاء</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة العملاء</span>
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
						@can('قائمة العملاء')
							<div class="card mg-b-20">
								<div class="card-header pb-0">
									<div class="d-flex justify-content-between">
										<h4 class="card-title mg-b-0"> قائمة العملاء </h4>
										<i class="mdi mdi-dots-horizontal text-gray"></i>
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="example" class="table key-buttons text-md-nowrap">
											<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0">الاسم بالكامل</th>
													<th class="border-bottom-0">رقم الهاتف</th>
													<th class="border-bottom-0">رقم الهوية</th>
													<th class="border-bottom-0">البريد الالكتروني</th>
													<th class="border-bottom-0">العنوان</th>
													<th class="border-bottom-0">النوع</th>
													<th class="border-bottom-0">العمليات</th> 
												</tr>
											</thead>
											<tbody>
												@php
													$i = 0;
												@endphp
												@foreach ($users as $value)
													@php
														$i++;
													@endphp
													<tr>
														<td>{{$i}}</td>
														<td>{{$value->name}}</td>
														<td>{{$value->mobile}}</td>
														<td>{{$value->nationalid}}</td>
														<td>{{$value->email}}</td>
														<td>{{$value->address}}</td>
														<td>
															@if($value->gender == 1)
																<span class="tag tag-blue">ذكر</span>
															@elseif($value->gender == 2)
																<span class="tag tag-azure">أنثى </span>
															@endif
														</td>
														<td>
															{{-- @can('تغيير كلمة مرور عميل')
																<button class="btn btn-outline-dark btn-sm"
																	data-toggle="modal"
																	data-userid="{{ $value->id }}"
																	data-target="#change_password_user{{ $value->id }}">تغيير كلمة المرور</button>
															@endcan --}}
															@can('تعديل عميل')
																<a class="btn btn-sm btn-outline-primary" href="{{ route('users.edit',$value->id) }}">تعديل</a>
															@endcan
															@can('حذف عميل') 
																<button class="btn btn-outline-danger btn-sm"
																	data-toggle="modal"
																	data-userid="{{ $value->id }}"
																	data-target="#delete_user{{ $value->id }}">حذف</button>
															@endcan
														</td>
														<!-- change password -->
														<div class="modal fade" id="change_password_user{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
															aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title" id="exampleModalLabel">تغيير كلمة مرور العميل</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<form action="{{ route('admin.user_change_password') }}" method="post">
																		{{ csrf_field() }}
																		<div class="modal-body">
																			<p class="text-center">
																				<h6 style="color:red"> هل انت متاكد من عملية تغيير كلمة المرور للعميل ؟</h6>
																			</p>
																			<input type="hidden" name="userid" id="userid" value="{{ $value->id }}">
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
																			<button type="submit" class="btn btn-danger">تاكيد</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
														<script>
															$('#change_password_user'.$value->id).on('show.bs.modal', function(event) {
																var button = $(event.relatedTarget)
																var userid = button.data('userid')
																var modal = $(this)
																modal.find('.modal-body #userid').val(userid);
															})
														</script>
														<!-- delete -->
														<div class="modal fade" id="delete_user{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
															aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title" id="exampleModalLabel">حذف العميل</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<form action="{{ route('users.destroy',$value->id) }}" method="post">
																		<input type="hidden" name="_method" value="DELETE">
																		{{ csrf_field() }}
																		<div class="modal-body">
																			<p class="text-center">
																				<h6 style="color:red"> هل انت متاكد من عملية حذف العميل ؟</h6>
																			</p>
																			<input type="hidden" name="userid" id="userid" value="">
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
																			<button type="submit" class="btn btn-danger">تاكيد</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
														<script>
															$('#delete_user'.$value->id).on('show.bs.modal', function(event) {
																var button = $(event.relatedTarget)
																var userid = button.data('userid')
																var modal = $(this)
																modal.find('.modal-body #userid').val(userid);
															})
														</script>
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
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
@endsection