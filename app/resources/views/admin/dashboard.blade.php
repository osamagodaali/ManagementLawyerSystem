@extends('layouts.master')
@section('title')
لوحة التحكم الرئيسية
@stop
@section('css')
<!-- Maps css -->
<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
	<div class="left-content">
		<div>
			<h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">مرحبا </h2>
			<p class="mg-b-0">لوحة التحكم الرئيسية</p>
		</div>
	</div>
</div>
<!-- /breadcrumb -->
<!-- breadcrumb -->
{{-- <div class="breadcrumb-header justify-content-between">
	<div class=" d-flex my-xl-auto right-content">
		@can('قائمة القضايا')
			<div class="col-sm-1">
				<a href="{{ route('cases.index') }}" >
					<button type="button" class="btn btn-primary "><i class="mdi mdi-layers"></i>قائمة القضايا</button>
				</a>
			</div>
		@endcan
		@can('انشاء قضية')
			<div class="col-sm-1">
				<a href="{{ route('cases.create') }}" >
					<button type="button" class="btn btn-primary "><i class="mdi mdi-layers"></i>اضافة قضية</button>
				</a>
			</div>
		@endcan
		@can('قائمة العملاء')
			<div class="col-sm-1">
				<a href="{{ route('users.index') }}" >
					<button type="button" class="btn btn-success "><i class="mdi mdi-account-multiple"></i>قائمة العملاء  </button>
				</a>
			</div>
		@endcan
		@can('انشاء عميل')
			<div class="col-sm-1">
				<a href="{{ route('users.create') }}" >
					<button type="button" class="btn btn-success"><i class="mdi mdi-account-multiple-plus"></i>إضافة عميل</button>
				</a>
			</div>
		@endcan
		@can('قائمة الموظفين')
			<div class="col-sm-1">
				<a href="{{ route('employees.index') }}" >
					<button type="button" class="btn btn-warning  "><i class="mdi mdi-account-location"></i>قائمة الموظفين</button>
				</a>
			</div>
		@endcan
		@can('انشاء موظف')
			<div class="col-sm-1">
				<a href="{{ route('employees.create') }}" >
					<button type="button" class="btn btn-warning "><i class="mdi mdi-account-plus"></i>إضافة موظف</button>
				</a>
			</div>
		@endcan
		@can('قائمة الايرادات')
			<div class="col-sm-1">
				<a href="{{ route('revenues.index') }}" >
					<button type="button" class="btn btn-info"><i class="mdi mdi-database-plus"> </i>الإيرادات</button>
				</a>
			</div>
		@endcan
		@can('قائمة المصروفات')
			<div class="col-sm-1">
				<a href="{{ route('expenses.index') }}" >
					<button type="button" class="btn btn-info"><i class="mdi mdi-database-minus"></i>المصروفات</button>
				</a>
			</div>
		@endcan
		@can('الاعدادات')
			<div class="col-sm-1">
				<a href="{{ route('admin.setting') }}" >
					<button type="button" class="btn btn-dark "><i class="mdi mdi-settings"></i>الاعدادات </button>
				</a>
			</div>
		@endcan
		<div class="col-sm-1">
			<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
				<button type="button" class="btn btn-danger "><i class="mdi mdi-logout-variant"></i>خروج </button>
				<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
					@csrf
				</form>
			</a>
		</div>
	</div>
</div> --}}
<!-- /breadcrumb -->
@endsection
@section('content')
	@can('لوحة التحكم')
		<!-- row -->
		<div class="row row-sm">
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
		</div>
		<div class="row row-sm">
			<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
				<div class="card overflow-hidden sales-card bg-primary-gradient">
					<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
						<div class="">
							<h6 class="mb-3 tx-12 text-white">إجمالي القضايا</h6>
						</div>
						<a href="{{ route('cases.index') }}" >
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">{{ $all_cases }}</h4>
										<p class="mb-0 tx-12 text-white op-7">إجمالي القضايا</p>
									</div>
									<span class="float-right my-auto mr-auto">
										<i class="fas fa-arrow-circle-up text-white"></i>
										<span class="text-white op-7"> {{ $all_cases }}</span>
									</span>
								</div>
							</div>
						</a>
					</div>
					<span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
				</div>
			</div>
			<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
				<div class="card overflow-hidden sales-card bg-danger-gradient">
					<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
						<div class="">
							<h6 class="mb-3 tx-12 text-white">القضايا في قائمة الانتظار</h6>
						</div>
						<a href="{{ route('admin.new_cases') }}" >
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">{{ $new_cases }}</h4>
										<p class="mb-0 tx-12 text-white op-7">القضايا في قائمة الانتظار</p>
									</div>
									<span class="float-right my-auto mr-auto">
										<i class="fas fa-arrow-circle-up text-white"></i>
										<span class="text-white op-7"> {{ $new_cases }}</span>
									</span>
								</div>
							</div>
						</a>
					</div>
					<span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
				</div>
			</div>
			<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
				<div class="card overflow-hidden sales-card bg-success-gradient">
					<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
						<div class="">
							<h6 class="mb-3 tx-12 text-white">القضايا الجارية</h6>
						</div>
						<a href="{{ route('admin.current_cases') }}" >
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">{{ $current_cases }}</h4>
										<p class="mb-0 tx-12 text-white op-7">القضايا الجارية</p>
									</div>
									<span class="float-right my-auto mr-auto">
										<i class="fas fa-arrow-circle-up text-white"></i>
										<span class="text-white op-7"> {{ $current_cases }}</span>
									</span>
								</div>
							</div>
						</a>
					</div>
					<span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
				</div>
			</div>
			<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
				<div class="card overflow-hidden sales-card bg-warning-gradient">
					<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
						<div class="">
							<h6 class="mb-3 tx-12 text-white">القضايا المنتهية</h6>
						</div>
						<a href="{{ route('admin.finished_cases') }}" >
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">{{ $finished_cases }}</h4>
										<p class="mb-0 tx-12 text-white op-7">القضايا المنتهية</p>
									</div>
									<span class="float-right my-auto mr-auto">
										<i class="fas fa-arrow-circle-up text-white"></i>
										<span class="text-white op-7"> {{ $finished_cases }}</span>
									</span>
								</div>
							</div>
						</a>
					</div>
					<span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-lg-12 col-xl-12">
			<div class="card card-table-two">
				<div class="d-flex justify-content-between">
					<h4 class="card-title mb-1">تنبهات القضايا القادمة</h4>
					<i class="mdi mdi-dots-horizontal text-gray"></i>
				</div>
				<span class="tx-12 tx-muted mb-3 ">قائمة تنبيهات للقضايا قبل الموعد ب عدد 2 يوم</span>
				<div class="table-responsive country-table">
					<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
						<thead>
							<tr>
								<th class="wd-lg-25p tx-right">موعد الجلسة القادم</th>
								<th class="wd-lg-25p tx-right">اسم العميل</th>
								<th class="wd-lg-25p tx-right">رقم القضية</th>
								<th class="wd-lg-25p tx-right">الفرع</th>
								<th class="wd-lg-25p tx-right">المحكمة</th>
								<th class="wd-lg-25p tx-right">الدائرة القضائية</th>
								<th class="wd-lg-25p tx-right">نوع القضية </th>
							</tr>
						</thead>
						<tbody>
							@foreach($next_cases as $case)
								<tr>
									<td>{{ $case->nextfollowdate }}</td>
									<td class="tx-right tx-medium tx-inverse">{{$case->username}}</td>
									<td class="tx-right tx-medium tx-inverse">{{$case->case_number}}</td>
									<td class="tx-right tx-medium tx-inverse">{{$case->branchename}}</td>
									<td class="tx-right tx-medium tx-inverse">{{$case->courtname}}</td>
									<td class="tx-right tx-medium tx-inverse">{{$case->judicial_chamber_name}}</td>
									<td class="tx-right tx-medium tx-inverse">{{$case->case_type_name}}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row row-sm">
			<div class="col-xl-4 col-md-12 col-lg-12">
				<div class="card">
					<div class="card-header pb-1">
						<h3 class="card-title mb-2">انواع القضايا</h3>
						<p class="tx-12 mb-0 text-muted">عدد القضايا لكل نوع من انواع القضايا</p>
					</div>
					<div class="card-body p-0 customers mt-1">
						<div class="list-group list-lg-group list-group-flush">
							@foreach($casesTypes as $cType)
								@php
								$admin_id = Auth::user()->id;
								$admin_data = App\Models\Admin::find($admin_id);
								$cases_availabe = App\Models\Admin::where('id',$admin_id)->value('cases_availabe'); 
								if($cases_availabe == 0){
									$count_cases_type = App\Models\Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
													->where('admin_has_cases_roles.admin_id' , $admin_id)
													->where('cases.case_type','=',$cType->id)
													->count();
								}elseif($cases_availabe == 1){
									$count_cases_type = App\Models\Cases::where('cases.case_type','=',$cType->id)->get()->count();
								}	
								@endphp
								<div class="list-group-item list-group-item-action" href="#">
									<div class="media mt-0">
										<img class="avatar-lg rounded-circle ml-3 my-auto" src="{{URL::asset('assets/img/faces/3.jpg')}}" alt="Image description">
										<div class="media-body">
											<div class="d-flex align-items-center">
												<div class="mt-0">
													<h5 class="mb-1 tx-15">{{ $cType->name }}</h5>
													<p class="mb-0 tx-13 text-muted">{{$count_cases_type}} <span class="text-danger ml-2">عدد القضايا</span></p>
												</div>
												<span class="mr-auto wd-45p fs-16 mt-2">
													{{-- <div id="spark1" class="wd-100p"></div> --}}
												</span>
											</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-4 col-md-12 col-lg-6">
				<div class="card">
					<div class="card-header pb-1">
						<h3 class="card-title mb-2">احدث القضايا</h3>
						<p class="tx-12 mb-0 text-muted">اخر عدد 5 قضايا تم تسجيلها</p>
					</div>
					<div class="product-timeline card-body pt-2 mt-1">
						<ul class="timeline-1 mb-0">
							@if(count($last_5_cases) > 0)
								@foreach($last_5_cases as $case)
									@if($case->case_status == 1)
										<li class="mt-0 mb-0"> 
											<i class="icon-note icons bg-danger-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 "> قضية رقم {{ $case->case_number}}</span> <a href="#" class="float-left tx-11 text-muted">{{ $case->created_at}}</a>
											<a href="{{ route('cases.show',$case->id) }}"><p class="mb-0 text-muted tx-12">مشاهدة تفاصيل القضية</p></a>
										</li>
										<br>
									@elseif($case->case_status == 2)
										<li class="mt-0"> <i class="icon-note icons bg-success-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 ">قضية رقم {{ $case->case_number}} </span> <a href="#" class="float-left tx-11 text-muted">{{ $case->created_at}}</a>
											<a href="{{ route('cases.show',$case->id) }}"><p class="mb-0 text-muted tx-12">مشاهدة تفاصيل القضية</p></a>
										</li>
										<br>
									@elseif($case->case_status == 3)
										<li class="mt-0"> <i class="icon-note icons bg-warning-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 ">قضية رقم {{ $case->case_number}} </span> <a href="#" class="float-left tx-11 text-muted">{{ $case->created_at}}</a>
											<a href="{{ route('cases.show',$case->id) }}"><p class="mb-0 text-muted tx-12">مشاهدة تفاصيل القضية</p></a>
										</li>
										<br>
									@elseif($case->case_status == 4)
										<li class="mt-0"> <i class="icon-note icons bg-primary-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 ">قضية رقم {{ $case->case_number}} </span> <a href="#" class="float-left tx-11 text-muted">{{ $case->created_at}}</a>
											<a href="{{ route('cases.show',$case->id) }}"><p class="mb-0 text-muted tx-12">مشاهدة تفاصيل القضية</p></a>
										</li>
										<br>
									@elseif($case->case_status == 5)
										<li class="mt-0"> <i class="icon-note icons bg-secondary-gradient text-white product-icon"></i> <span class="font-weight-semibold mb-4 tx-14 ">قضية رقم {{ $case->case_number}} </span> <a href="#" class="float-left tx-11 text-muted">{{ $case->created_at}}</a>
											<a href="{{ route('cases.show',$case->id) }}"><p class="mb-0 text-muted tx-12">مشاهدة تفاصيل القضية</p></a>
										</li>
										<br>
									@endif
									
								@endforeach
							@endif
						</ul>
					</div>
				</div>
			</div>
			@can('قائمة العملاء')
				<div class="col-md-12 col-lg-4 col-xl-4">
					<div class="card card-dashboard-eight pb-2">
						<h6 class="card-title">احدث عملاء</h6><span class="d-block mg-b-10 text-muted tx-12">أحدث عدد 5 عملاء جدد</span>
						<div class="list-group">
							@foreach($last_5_users as $user)
								<div class="list-group-item border-top-0">
									{{-- <i class="flag-icon flag-icon-eg flag-icon-squared"></i> --}}
									<p>{{ $user->name }}</p><span>{{ $user->mobile }}</span>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			@endcan
		</div>
		<!-- row -->
	</div>
	@endcan
</div>
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Moment js -->
<script src="{{URL::asset('assets/plugins/raphael/raphael.min.js')}}"></script>
<!--Internal  Flot js-->
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
<script src="{{URL::asset('assets/js/dashboard.sampledata.js')}}"></script>
<script src="{{URL::asset('assets/js/chart.flot.sampledata.js')}}"></script>
<!--Internal Apexchart js-->
<script src="{{URL::asset('assets/js/apexcharts.js')}}"></script>
<!-- Internal Map -->
<script src="{{URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{URL::asset('assets/js/modal-popup.js')}}"></script>
<!--Internal  index js -->
<script src="{{URL::asset('assets/js/index.js')}}"></script>
<script src="{{URL::asset('assets/js/jquery.vmap.sampledata.js')}}"></script>	
@endsection