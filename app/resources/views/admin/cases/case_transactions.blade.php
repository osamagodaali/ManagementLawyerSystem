@extends('layouts.master')
@section('title')
تفاصيل المعاملات للقضية
@stop
@section('css')
    <!--- Custom-scroll -->
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">قائمة القضايا</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تفاصيل المعاملات للقضية</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row opened -->
    <div class="row row-sm">
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
            <!-- div -->
            {{-- @can('تفاصيل المعاملات للقضية') --}}
                <div class="card mg-b-20" id="tabs-style2">
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="example">
                                <div class="panel panel-primary tabs-style-2">
                                    <div class=" tab-menu-heading">
                                        <div class="tabs-menu1">
                                            <!-- Tabs -->
                                            <ul class="nav panel-tabs main-nav-line">
                                                <li><a href="#tab1" class="nav-link active" data-toggle="tab">الايرادات  </a>
                                                </li>
                                                <li><a href="#tab2" class="nav-link" data-toggle="tab">المصروفات  </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body tabs-menu-body main-content-body-right border">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab1">
                                                <div class="table-responsive mt-15">
                                                    <table class="table center-aligned-table mb-0 table-hover"
                                                        style="text-align:center">
                                                        <thead>
                                                            <tr class="text-dark">
                                                                <th>#</th>
                                                                <th>رقم القضية </th>
                                                                <th>المبلغ</th>
                                                                <th>التفاصيل</th>
                                                                <th>الدفع بواسطة </th>
                                                                <th>تاريخ </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $i = 0;
                                                            @endphp
                                                            @foreach ($revenues as $x)
                                                                @php
                                                                    $i++;
                                                                    $case = App\Models\Cases::where('id', $x->caseid)->first();
                                                                    $follow_by = App\Models\Admin::where('id', $x->addedby)->first();
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $i }}</td>
                                                                    <td>{{ $case->case_number }}</td>
                                                                    <td>{{ $x->value }}</td>
                                                                    <td>{{ $x->details }}</td>
                                                                    <td>{{ $follow_by->name }}</td>
                                                                    <td>{{ $x->created_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="tab2">
                                                <div class="table-responsive mt-15">
                                                    <table class="table center-aligned-table mb-0 table-hover"
                                                        style="text-align:center">
                                                        <thead>
                                                            <tr class="text-dark">
                                                                <th>#</th>
                                                                <th>رقم القضية </th>
                                                                <th>المبلغ</th>
                                                                <th>التفاصيل</th>
                                                                <th>الدفع بواسطة </th>
                                                                <th>تاريخ </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $i = 0;
                                                            @endphp
                                                            @foreach ($expenses as $x)
                                                                @php
                                                                    $i++;
                                                                    $case = App\Models\Cases::where('id', $x->caseid)->first();
                                                                    $follow_by = App\Models\Admin::where('id', $x->addedby)->first();
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $i }}</td>
                                                                    <td>{{ $case->case_number }}</td>
                                                                    <td>{{ $x->value }}</td>
                                                                    <td>{{ $x->details }}</td>
                                                                    <td>{{ $follow_by->name }}</td>
                                                                    <td>{{ $x->created_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @endcan --}}
            <!-- /div -->
        </div>
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Internal Input tags js-->
    <script src="{{ URL::asset('assets/plugins/inputtags/inputtags.js') }}"></script>
    <!--- Tabs JS-->
    <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ URL::asset('assets/js/tabs.js') }}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>

@endsection
