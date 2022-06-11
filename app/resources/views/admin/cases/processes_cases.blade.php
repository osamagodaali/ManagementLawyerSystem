@extends('layouts.master')
@section('title')
قائمة القضايا في قائمة العمليات
@stop
@section('css')
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<!-- Internal Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/pickerjs/picker.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
	<!-- breadcrumb -->
	<div class="breadcrumb-header justify-content-between">
		<div class="my-auto">
			<div class="d-flex">
				<h4 class="content-title mb-0 my-auto">القضايا</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة القضايا في قائمة الانتظار</span>
			</div>
		</div>
	</div>
	<!-- breadcrumb --> 
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
			<div class="card overflow-hidden">
				<div class="card-body">
					<form action="{{ route('admin.newcasesfilter') }}" method="POST" autocomplete="off">
						{{ csrf_field() }}
						<div class="row row-sm mg-b-20">
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10"> اسم العميل </p>
								<select class="form-control select2" name="username" >
									<option value="">اختر اسم العميل</option>
									@foreach($users as $user)
										<option value="{{ $user->id }}">{{ $user->name }} </option>
									@endforeach
								</select>
							</div>
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10">  الرقم القومي</p>
								<select class="form-control select2" name="usernationalid" >
									<option label="اختر الرقم القومي"></option>
									@foreach($users as $user)
										<option value="{{ $user->id }}"> {{ $user->nationalid }} </option>
									@endforeach
								</select>
							</div>
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10">رقم الهاتف </p>
								<select class="form-control select2" name="usermobile" >
									<option label="اختر رقم الهاتف"></option>
									@foreach($users as $user)
										<option value="{{ $user->id }}">{{ $user->mobile }} </option>
									@endforeach
								</select>
							</div>
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10">رقم القضية</p>
								<select class="form-control select2" name="case_number" >
									<option label="اختر رقم القضية"></option>
									@foreach($allCases as $value)
										<option value="{{ $value->case_number }}">{{ $value->case_number }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10"> نوع القضية</p>
								<select class="form-control select2" name="case_type" > 
									<option label="اختر نوع القضية "></option>
									@foreach($casesType as $cType)
										<option value="{{ $cType->id }}">{{ $cType->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10">المحكمة</p>
								<select class="form-control select2" name="courtid" id="courts-dropdown" > 
									<option label="اختر المحكمة "></option>
									@foreach($courts as $court)
										<option value="{{ $court->id }}">{{ $court->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10">الدائرة القضائية</p>
								<select class="form-control select2" name="judicialchamberid" id="JudicialChambers-dropdown"> 

								</select>
							</div>
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10">حالة الدفع</p>
								<select class="form-control select2" name="payment_status" > 
									<option label="اختر حالة الدفع"></option>
									<option value="1">لم يتم الدفع </option>
									<option value="2"> دفع جزئي</option>
									<option value="3"> دفع كامل</option>
								</select>
							</div>
							<div class="col-lg-2 mg-t-20 mg-lg-t-0">
								<p class="mg-b-10">الفرع </p>
								<select class="form-control select2" name="branche" > 
									<option label="اختر الفرع "></option>
									@foreach($branches as $branche)
										<option value="{{ $branche->id }}">{{ $branche->name }}</option>
									@endforeach
								</select>
							</div>
							
						</div>
						<span class="input-group-append">
							<button class="btn ripple btn-primary" type="submit">بحث</button>
						</span>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('content')
	@can('قائمة قضايا العمليات')
		<!-- row -->
		<div class="row">
			<!--div-->
			<div class="col-xl-12">
				<div class="card mg-b-20">
					<div class="card-header pb-0">
						<div class="d-flex justify-content-between">
							<h4 class="card-title mg-b-0"> قائمة القضايا في قائمة الانتظار </h4>
							<i class="mdi mdi-dots-horizontal text-gray"></i>
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table key-buttons text-md-nowrap">
								<thead>
									<tr>
										<th class="border-bottom-0">#</th>
										<th class="border-bottom-0">اسم العميل</th>
										<th class="border-bottom-0">الفرع</th>
										<th class="border-bottom-0">المحكمة</th>
										<th class="border-bottom-0">الدائرة القضائية</th>
										<th class="border-bottom-0">نوع القضية</th>
										<th class="border-bottom-0">رقم القضية</th>
										<th class="border-bottom-0">الصفة</th>
										<th class="border-bottom-0">مبلغ المطالبة</th>
										<th class="border-bottom-0">إجمالي الإيراد</th>
										<th class="border-bottom-0">إجمالي المصروف</th>
										<th class="border-bottom-0">حالة الدفع</th>
										<th class="border-bottom-0">العمليات</th> 
									</tr>
								</thead>
								<tbody>
									@php
										$i = 0;
									@endphp
									@foreach ($cases as $value)
										@php
											$i++;
											$case_revenues = App\Models\revenues::where("caseid", $value->id)->sum('value');
											$case_expenses = App\Models\Expenses::where("caseid", $value->id)->sum('value');
										@endphp
										<tr>
											<td>{{$i}}</td>
											<td>{{$value->name}}</td>
											<td>{{$value->branchename}}</td>
											<td>{{$value->court_name}}</td>
											<td>{{$value->judicial_chamber_name}}</td>
											<td>{{$value->cases_type_name}}</td>
											<td>{{$value->case_number}}</td>
											<td>{{$value->title}}</td>
											<td>
												<span class="badge badge-primary">{{$value->value}}</span>
											</td>
											<td>
												<span class="badge badge-success">{{$case_revenues}}</span>
											</td>
											<td>
												<span class="badge badge-dark">{{$case_expenses}}</span>
											</td>
											<td>
												@if($value->payment_status == 1 )
													<h5  class="text-success">لم يتم الدفع </h5>
												@elseif($value->payment_status == 2)
													<h5  class="text-info">دفع جزئي </h5>
												@elseif($value->payment_status == 3)
													<h5  class="text-primary">دفع كامل </h5>
												@endif
											</td>
											<td>
												@can('اضافة لسير القضية')
													<a class="btn btn-sm btn-outline-info" href="{{ route('admin.add_case_details_form',$value->id) }}">إضافة ل سير القضية</a>
												@endcan
												@can('مشاهدة قضية')
													<a class="btn btn-sm btn-outline-warning" href="{{ route('cases.show',$value->id) }}">مشاهدة تفاصيل القضية</a>
												@endcan
												@can('تغيير حالة القضية')
													<a class="btn btn-sm btn-outline-secondary" data-target="#modalChangeCaseStatus{{$value->id }}" data-toggle="modal" href="">تغيير حالة القضية</a>
												@endcan
												@can('التفاصيل المالية للقضية')
													<a class="btn btn-sm btn-outline-dark" href="{{ route('admin.case_transactions',$value->id) }}">التفاصيل المالية للقضية </a>
												@endcan
												@can('انشاء ايراد')
													<a class="btn btn-sm btn-outline-light" data-target="#modalAddRevenue{{$value->id }}" data-toggle="modal" href="">اضافة ايراد</a>
												@endcan
												@can('انشاء مصروف')
													<a class="btn btn-sm btn-outline-dark" data-target="#modalAddExpense{{$value->id }}" data-toggle="modal" href="">اضافة مصروف</a>
												@endcan
												@can('تعديل قضية')
													<a class="btn btn-sm btn-outline-primary" href="{{ route('cases.edit',$value->id) }}">تعديل</a>
												@endcan
												@can('حذف قضية')
													<button class="btn btn-outline-danger btn-sm" 
														data-toggle="modal"
														data-caseid="{{ $value->id }}"
														data-target="#delete_case{{ $value->id }}">حذف</button>
												@endcan
											</td>
										</tr>
										<!-- Basic modal  change case status  -->
										<div class="modal" id="modalChangeCaseStatus{{$value->id }}">
											<div class="modal-dialog" role="document">
												<div class="modal-content modal-content-demo">
													<form action="{{ route('admin.update_case_status') }}" method="post" enctype="multipart/form-data" autocomplete="off">
														<input type="hidden" name="_method" value="PATCH">
														{{ csrf_field() }} 
														<div class="modal-header">
															<h6 class="modal-title">تغير حالة القضية</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
														</div>
														<div class="modal-body">
															<input name="caseid" type="hidden" value="{{$value->id }}">

															<div class="row row-xs align-items-center mg-b-20">
																<div class="col-md-2">
																	<label class="form-label mg-b-0"> حالة القضية   </label>
																</div>
																<div class="col-md-10 ">
																	<select class="form-control select2" name="case_status" > 
																		<option label="اختر حالة القضية"></option>
																		<option value="1" {{ $value->case_status == 1 ? 'selected' : '' }} > في قائمة الانتظار</option>
																		<option value="2" {{ $value->case_status == 2 ? 'selected' : '' }} > جارية</option>
																		<option value="3" {{ $value->case_status == 3 ? 'selected' : '' }} > منتهية</option>
																		<option value="4" {{ $value->case_status == 4 ? 'selected' : '' }} > العمليات</option>
																		<option value="5" {{ $value->case_status == 5 ? 'selected' : '' }} > التحصيل</option>
																	</select>
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
										<!-- Basic modal  add revenue -->
										<div class="modal" id="modalAddRevenue{{$value->id }}">
											<div class="modal-dialog" role="document">
												<div class="modal-content modal-content-demo">
													<form action="{{ route('revenues.index') }}" method="post" enctype="multipart/form-data" autocomplete="off">
														{{ csrf_field() }} 
														<div class="modal-header">
															<h6 class="modal-title">إضافة إيراد جديد</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
														</div>
														<div class="modal-body">
															<input name="caseid" type="hidden" value="{{$value->id }}">
															<div class="row row-xs align-items-center mg-b-20">
																<div class="col-md-2">
																	<label class="form-label mg-b-0"> مبلغ المطالبة   </label>
																</div>
																<div class="col-md-10 mg-t-5 mg-md-t-0">
																	<input name="value" class="form-control" placeholder=" مبلغ المطالبة " type="number" >
																</div>
															</div>
															<div class="row row-xs align-items-center mg-b-20">
																<div class="col-md-2">
																	<label class="form-label mg-b-0">التفاصيل</label>
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
										<!-- Basic modal add expense -->
										<div class="modal" id="modalAddExpense{{$value->id }}">
											<div class="modal-dialog" role="document">
												<div class="modal-content modal-content-demo">
													<form action="{{ route('expenses.index') }}" method="post" enctype="multipart/form-data" autocomplete="off">
														{{ csrf_field() }} 
														<div class="modal-header">
															<h6 class="modal-title">إضافة مصروف جديد</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
														</div>
														<div class="modal-body">
															<input name="caseid" type="hidden" value="{{$value->id }}">
															<div class="row row-xs align-items-center mg-b-20">
																<div class="col-md-2">
																	<label class="form-label mg-b-0"> مبلغ المطالبة   </label>
																</div>
																<div class="col-md-10 mg-t-5 mg-md-t-0">
																	<input name="value" class="form-control" placeholder=" مبلغ المطالبة " type="number" >
																</div>
															</div>
															<div class="row row-xs align-items-center mg-b-20">
																<div class="col-md-2">
																	<label class="form-label mg-b-0">التفاصيل</label>
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
										<!-- Basic modal delete case -->
										<div class="modal fade" id="delete_case{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
											aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">حذف تفاصيل</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<form action="{{ route('cases.destroy', $value->id) }}" method="post">
														<input type="hidden" name="_method" value="DELETE">
														{{ csrf_field() }}
														<div class="modal-body">
															<p class="text-center">
																<h6 style="color:red"> هل انت متاكد من عملية حذف القضية ؟</h6>
															</p>
															<input type="hidden" name="caseid" id="caseid" value="">
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
											$('#delete_case'.$value->id).on('show.bs.modal', function(event) {
												var button = $(event.relatedTarget)
												var caseid = button.data('caseid')
												var modal = $(this)
												modal.find('.modal-body #caseid').val(caseid);
											})
										</script>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!--/div-->
		</div>
	@endcan
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
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!--Internal quill js -->
<script src="{{URL::asset('assets/plugins/quill/quill.min.js')}}"></script>
<!-- Internal Form-editor js -->
<script src="{{URL::asset('assets/js/form-editor.js')}}"></script>
<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js')}}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
<!-- Ionicons js -->
<script src="{{URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>
<!--Internal  pickerjs js -->
<script src="{{URL::asset('assets/plugins/pickerjs/picker.min.js')}}"></script>
<!-- Internal form-elements js -->
<script src="{{URL::asset('assets/js/form-elements.js')}}"></script>
<script>
	$(document).ready(function() {
		$('#courts-dropdown').on('change', function() {
			var courtid = this.value;
			$("#JudicialChambers-dropdown").html('');
			$.ajax({
				url:"{{route('admin.getjudicialchambers')}}",
				type: "POST",
				data: {
					courtid: courtid,
					_token: '{{csrf_token()}}' 
				},
				dataType : 'json',
				success: function(result){
					$('#JudicialChambers-dropdown').html('<option label="اختر الدائرة القضائية "></option>'); 
					$.each(result.JudicialChambers,function(key,value){
						$("#JudicialChambers-dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
					});
		
				}
			});
		});
	});
</script>
@endsection