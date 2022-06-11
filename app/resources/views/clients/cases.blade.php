@extends('layouts.clients.master')
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
                <h4 class="content-title mb-0 my-auto">الطلبات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الطلبات</span>
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
                @if (session()->has('Add'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('Add') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                @endif
                @if (session()->has('Update'))
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('Update') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                @endif
                @if (session()->has('Delete'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('delete') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                {{-- @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <div class="alert alert-success mg-b-0" role="alert">
                            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>{{ \Session::get('success') }}</strong>
                        </div>
                    </div>
                @endif
                @if (\Session::has('error'))
                    <div class="alert alert-danger">
                        <div class="alert alert-danger mg-b-0" role="alert">
                            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>{{ \Session::get('error') }}</strong>
                        </div>
                    </div>
                @endif --}}
                <div class="card mg-b-20">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mg-b-0">قائمة الطلبات</h4>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table key-buttons text-md-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">بيانات القضية </th>
                                        <th class="border-bottom-0"> التفاصيل</th>
                                        <th class="border-bottom-0"> القيمة </th>
                                        <th class="border-bottom-0">حالة القضية</th>
                                        <th class="border-bottom-0">حالة الدفع</th>
                                        <th class="border-bottom-0">التاريخ</th>
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
                                        @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$value->case_number}} - {{$value->title}}</td>
                                            <td>{{$value->details}}</td>
                                            <td>{{$value->value}}</td>
                                            <td>
                                                @if($value->case_status == 0)
                                                    <span class="tag tag-azure">جديدة </span>
                                                @elseif($value->case_status == 1)
                                                    <span class="tag tag-green">جاري</span>
                                                @elseif($value->case_status == 2)
                                                    <span class="tag tag-red">منتهية</span>
                                                @endif 
                                            </td>
                                            <td>
                                                @if($value->payment_status == 0)
                                                    <span class="tag tag-azure">لم يتم الدفع</span>
                                                @elseif($value->payment_status == 1)
                                                    <span class="tag tag-green">دفع جزئي</span>
                                                @elseif($value->payment_status == 2)
                                                    <span class="tag tag-red">دفع كامل</span>
                                                @endif
                                            </td>
                                            <td>{{$value->created_at}}</td>
                                            <td>
                                                <a class="btn btn-outline-primary btn-sm" href="{{ route('user.cases_details',$value->id) }}" role="button"><i class="fas fa-download"></i>&nbsp;
                                                   تفاصيل سير القضية
                                                </a>       
                                            </td>
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

@endsection