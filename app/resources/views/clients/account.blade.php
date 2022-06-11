@extends('layouts.clients.master')
@section('css')
<!-- Internal Data table css -->
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
							<h4 class="content-title mb-0 my-auto">حسابي</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل حسابي</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
                <!-- row -->
				<div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="card  bg-primary-gradient">
							<div class="card-body">
								<div class="counter-status d-flex md-mb-0">
									<div class="counter-icon">
										<i class="icon icon-people"></i>
									</div>
									<div class="mr-auto">
										<h5 class="tx-13 tx-white-8 mb-3">صافي الرصيد</h5>
										<h2 class="counter mb-0 text-white">{{$wallet_value}}</h2>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="card  bg-danger-gradient">
							<div class="card-body">
								<div class="counter-status d-flex md-mb-0">
									<div class="counter-icon text-warning">
										<i class="icon icon-rocket"></i>
									</div>
									<div class="mr-auto">
										<h5 class="tx-13 tx-white-8 mb-3">اجمالي المدفوع</h5>
										<h2 class="counter mb-0 text-white">{{$total_user_revenue}}</h2>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="card  bg-success-gradient">
							<div class="card-body">
								<div class="counter-status d-flex md-mb-0">
									<div class="counter-icon text-primary">
										<i class="icon icon-docs"></i>
									</div>
									<div class="mr-auto">
										<h5 class="tx-13 tx-white-8 mb-3">اجمالي الخصومات</h5>
										<h2 class="counter mb-0 text-white">{{$total_user_expenses}}</h2>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- row closed -->
				<!-- row opened -->
				<div class="row row-sm">
					<!--div-->
					<div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<h4 class="card-title mg-b-0">حسابي</h4>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
								<p class="tx-12 tx-gray-500 mb-2">تفاصيل حسابي </p>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example" class="table key-buttons text-md-nowrap">
										<thead>
											<tr>
												<th class="border-bottom-0">#</th>
												<th class="border-bottom-0">قيمة المعاملة</th>
												<th class="border-bottom-0">نوع المعاملة</th>
                                                <th class="border-bottom-0">تفاصيل المعاملة</th>
												<th class="border-bottom-0">التاريخ</th>
											</tr>
										</thead>
										<tbody>
                                            @php
												$i = 0;
											@endphp
											@foreach ($wallet_transactions as $value)
												@php
													$i++;
												@endphp
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$value->value}}</td>
                                                    <td>
                                                        @if($value->type == 1)
                                                            <span class="tag tag-green">إضافة</span>
                                                        @elseif($value->type == 2)
                                                            <span class="tag tag-red">خصم</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$value->details}}</td>
                                                    <td>{{$value->created_at}}</td>
                                                </tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!--/div-->
				</div>
				<!-- /row -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
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
@endsection