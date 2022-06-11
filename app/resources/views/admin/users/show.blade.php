@extends('layouts.master')
@section('title')
بيانات العميل الجديد
@stop
@section('css')
    <style>
        @media print {
            #print_Button {
                display: none;
            }
        }
    </style>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">العملاء</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/  بيانات العميل الجديد  </span>
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
                    <div class="card" id="print" >
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mg-b-0">بياناتات العميل الجديد</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mg-b-0 text-md-nowrap">
                                    <tbody>
                                        <tr>
                                            <th scope="row">الاسم بالكامل</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">رقم الهاتف</th>
                                            <td>{{ $user->mobile }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">رقم الهوية</th>
                                            <td>{{ $user->nationalid }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">البريد الالكتروني</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">كلمة المرور</th>
                                            <td>{{ $user->random_password }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- bd -->
                        </div><!-- bd -->
                    </div><!-- bd -->
                    <button class="btn btn-danger  float-left mt-3 mr-2" id="print_Button" onclick="printDiv()"> <i
                        class="mdi mdi-printer ml-1"></i>طباعة</button>
                @endcan
			</div>
			<!--/div-->
        </div>
        <!-- /row -->
        </div>
    </div>
    <!-- Container closed -->
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script type="text/javascript">
        function printDiv() {
            var printContents = document.getElementById('print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endsection
