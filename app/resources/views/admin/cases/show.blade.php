@extends('layouts.master')
@section('title')
تفاصيل القضية
@stop
@section('css')
    <!--- Custom-scroll -->
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
@endsection
@section('title')
    تفاصيل القضية
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">قائمة القضايا</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تفاصيل القضية</span>
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
            @can('مشاهدة قضية')
                <div class="card mg-b-20" id="tabs-style2">
                    <div class="card-body">
                        <div class="text-wrap">
                            <div class="example">
                                <div class="panel panel-primary tabs-style-2">
                                    <div class=" tab-menu-heading">
                                        <div class="tabs-menu1">
                                            <!-- Tabs -->
                                            <ul class="nav panel-tabs main-nav-line">
                                                <li><a href="#tab1" class="nav-link active" data-toggle="tab">بيانات القضية </a>
                                                </li>
                                                <li><a href="#tab2" class="nav-link" data-toggle="tab">تفاصيل سير القضية
                                                    </a></li>
                                                <li><a href="#tab3" class="nav-link" data-toggle="tab">المرفقات</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body tabs-menu-body main-content-body-right border">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab1">
                                                <div class="table-responsive mt-15">
                                                    <table class="table table-striped" style="text-align:center">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">اسم العميل </th>
                                                                <td>{{ $user->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">رقم التليفون </th>
                                                                <td>{{ $user->mobile }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">الفرع</th>
                                                                <td>{{ $branche->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">المحكمة</th>
                                                                <td>{{ $courts->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">الدائرة القضائية</th>
                                                                <td>{{ $judicialchamber->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">نوع القضية</th>
                                                                <td>{{ $casesType->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">رقم القضية</th>
                                                                <td>{{ $case->case_number }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">الصفه</th>
                                                                <td>{{ $case->title }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">مبلغ المطالبة</th>
                                                                <td>{{ $case->value }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">حالة القضية </th>
                                                                <td>
                                                                    @if ($case->case_status == 1)
                                                                        في قائمة الانتظار
                                                                    @elseif($case->case_status == 2)
                                                                        جارية
                                                                    @elseif($case->case_status == 3)
                                                                        منتهية
                                                                    @elseif($case->case_status == 4)
                                                                        العمليات
                                                                    @elseif($case->case_status == 5)
                                                                        التحصيل
                                                                    @endif()
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">حالة الدفع</th>
                                                                <td>
                                                                    @if ($case->payment_status == 1)
                                                                        لم يتم الدفع
                                                                    @elseif($case->payment_status == 2)
                                                                        دفع جزئي
                                                                    @elseif($case->payment_status == 3)
                                                                        دفع كامل
                                                                    @endif()
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">تاريخ بداية القضية</th>
                                                                <td>{{ $case->start_case }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">تاريخ نهاية القضية</th>
                                                                <td>{{ $case->end_case }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">تاريخ الانشاء </th>
                                                                <td>{{ $case->created_at }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">المحامين المتابعيت للقضية </th>
                                                                <td>
                                                                    @foreach ($employees as $value)
                                                                        <span
                                                                            class="badge badge-pill badge-primary">{{ $value->name }}</span>
                                                                    @endforeach()
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">الوصف العام للقضية</th>
                                                                <td>{{ $case->details }}</td>
                                                            </tr>
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
                                                                <th>رقم سير القضية </th>
                                                                <th>القرارات على القضية</th>
                                                                <th>الاجراء المقترح</th>
                                                                <th>التفاصيل</th>
                                                                <th>التسجيل بواسطة </th>
                                                                <th>تاريخ المتابعة القادمة </th>
                                                                <th>تاريخ الاضافة</th>
                                                                <th>العمليات </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $i = 0;
                                                            @endphp
                                                            @foreach ($case_details as $x)
                                                                @php
                                                                    $i++;
                                                                    $follow_by = App\Models\Admin::where('id', $x->followby)->first();
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $i }}</td>
                                                                    <td>{{ $x->id }}</td>
                                                                    <td>{{ $x->case_decisions }}</td>
                                                                    <td>{{ $x->suggested_action }}</td>
                                                                    <td>{{ $x->details }}</td>
                                                                    <td>{{ $follow_by->name }}</td>
                                                                    <td>{{ $x->nextfollowdate }}</td>
                                                                    <td>{{ $x->created_at }}</td>
                                                                    <td>
                                                                        @can('تعديل لسير القضية')
                                                                            <a class="btn btn-sm btn-outline-success"
                                                                                href="{{ route('admin.edit_case_details_form', $x->id) }}">تعديل</a>
                                                                        @endcan
                                                                        @can('حذف لسير القضية')
                                                                            <button class="btn btn-outline-danger btn-sm"
                                                                                data-toggle="modal"
                                                                                data-casedetailsid="{{ $x->id }}"
                                                                                data-target="#delete_case_details">حذف</button>
                                                                        @endcan
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab3">
                                                <!--المرفقات-->
                                                <div class="card card-statistics">
                                                    <div class="table-responsive mt-15">
                                                        <table class="table center-aligned-table mb-0 table table-hover"
                                                            style="text-align:center">
                                                            <thead>
                                                                <tr class="text-dark">
                                                                    <th scope="col">م</th>
                                                                    <th scope="col">اسم الملف</th>
                                                                    <th scope="col">قام بالاضافة</th>
                                                                    <th scope="col">تاريخ الاضافة</th>
                                                                    <th scope="col">العمليات</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $i = 0;
                                                                @endphp
                                                                @foreach ($case_attachments as $attachment)
                                                                    @php
                                                                        $i++;
                                                                        $follow_by = App\Models\Admin::where('id', $attachment->uploadedby)->first();
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $i }}</td>
                                                                        <td>{{ $attachment->file_name }}</td>
                                                                        <td>{{ $follow_by->name }}</td>
                                                                        <td>{{ $attachment->created_at }}</td>
                                                                        <td colspan="2">
                                                                            @can('تحميل المرفق')
                                                                                <a class="btn btn-outline-primary btn-sm"
                                                                                    href="{{ route('admin.view_file', $attachment->id) }}"
                                                                                    role="button"><i
                                                                                        class="fas fa-eye"></i>&nbsp; عرض </a>
                                                                            @endcan
                                                                            @can('حذف المرفق')
                                                                                <button class="btn btn-outline-danger btn-sm"
                                                                                    data-toggle="modal"
                                                                                    data-fileid="{{ $attachment->id }}"
                                                                                    data-target="#delete_file">حذف</button>
                                                                            @endcan
                                                                        </td>
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
                </div>
            @endcan
            <!-- /div -->
        </div>
    </div>
    <!-- /row -->

    <!-- delete -->
    <div class="modal fade" id="delete_case_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف تفاصيل</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.case_details_destroy') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p class="text-center">
                        <h6 style="color:red"> هل انت متاكد من عملية حذف سير القضية ؟</h6>
                        </p>
                        <input type="hidden" name="casedetailsid" id="casedetailsid" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- delete -->
    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.file_destroy') }}" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p class="text-center">
                        <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                        </p>
                        <input type="hidden" name="fileid" id="fileid" value="">
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

    <script>
        $('#delete_case_details').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var casedetailsid = button.data('casedetailsid')
            var modal = $(this)
            modal.find('.modal-body #casedetailsid').val(casedetailsid);
        })
        $('#delete_file').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var fileid = button.data('fileid')
            var modal = $(this)
            modal.find('.modal-body #fileid').val(fileid);
        })
    </script>

    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>

@endsection
