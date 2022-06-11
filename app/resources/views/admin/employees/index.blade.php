@extends('layouts.master')
@section('title')
قائمة الموظفين
@stop
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الموظفين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                    الموظفين</span>
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
            @can('قائمة الموظفين')
                <div class="card mg-b-20">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mg-b-0">قائمة الموظفين</h4>
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
                                        <th class="border-bottom-0">البريد الالكتروني</th>
                                        <th class="border-bottom-0">عدد القضايا</th>
                                        <th class="border-bottom-0">مشاهده القضايا</th>
                                        <th class="border-bottom-0">الادوار </th>
                                        <th class="border-bottom-0">العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($employees as $value)
                                        @php
                                            $i++;
                                            $employee_role = DB::table('roles')
                                                ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
                                                ->where('model_has_roles.model_id', $value->id)
                                                ->get('roles.name');
                                            $cases_followed = App\Models\admin_has_cases_roles::where("admin_id", $value->id)->count();
                                        @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->mobile }}</td>
                                            <td>{{ $value->email }}</td>
                                            <td>
                                                <a href="{{ route('admin.emplyee_cases', $value->id)}}" >
                                                {{ $cases_followed }}  قضية
                                                </a>
                                            </td>
                                            <td>
                                                @if($value->cases_availabe == 0)
                                                    <span class="tag tag-blue">القضايا الخاصه فقط</span>
                                                @elseif($value->cases_availabe == 1)
                                                    <span class="tag tag-azure">كل القضايا </span>
                                                @endif
                                            </td>
                                            <td>
                                                @foreach ($employee_role as $v)
                                                    <span class="badge badge-pill badge-primary">{{ $v->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @can('مشاهدة نشاط الموظف')
                                                    <a class="btn btn-sm btn-outline-warning" href="{{ route('admin.show_activity',$value->id) }}">مشاهدة نشاط الموظف</a>
                                                @endcan
                                                @can('تغيير كلمة مرور موظف')
                                                    <button class="btn btn-outline-dark btn-sm" data-toggle="modal"
                                                    data-employeeid1="{{ $value->id }}"
                                                    data-target="#change_password_employee{{ $value->id }}">تغيير كلمة المرور</button>
                                                @endcan
                                                @can('تعديل موظف')
                                                    <a class="btn btn-sm btn-outline-primary"
                                                    href="{{ route('employees.edit', $value->id) }}">تعديل</a>
                                                @endcan
                                                @can('حذف موظف')
                                                    <button class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                                    data-employeeid="{{ $value->id }}"
                                                    data-target="#delete_employee{{ $value->id }}">حذف</button>
                                                @endcan
                                            </td>
                                            <!-- change password --> 
                                            <div class="modal fade" id="change_password_employee{{ $value->id }}" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">تغيير كلمة مرور الموظف</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('admin.change_password') }}" method="post">
                                                            {{ csrf_field() }}
                                                            <div class="modal-body">
                                                                <p class="text-center">
                                                                <h6 style="color:red"> هل انت متاكد من عملية تغيير كلمة المرور للموظف ؟</h6>
                                                                </p>
                                                                <input type="hidden" name="employeeid1" id="employeeid1" value="{{ $value->id }}">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">الغاء</button>
                                                                <button type="submit" class="btn btn-danger">تاكيد</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- delete --> 
                                            <div class="modal fade" id="delete_employee{{ $value->id }}" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">حذف الموظف</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('employees.destroy', $value->id) }}" method="post">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            {{ csrf_field() }}
                                                            <div class="modal-body">
                                                                <p class="text-center">
                                                                <h6 style="color:red"> هل انت متاكد من عملية حذف الموظف ؟</h6>
                                                                </p>
                                                                <input type="hidden" name="employeeid" id="employeeid" value="">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">الغاء</button>
                                                                <button type="submit" class="btn btn-danger">تاكيد</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                $('#change_password_employee'.$value->id).on('show.bs.modal', function(event) {
                                                    var button = $(event.relatedTarget)
                                                    var employeeid1 = button.data('employeeid1')
                                                    var modal = $(this)
                                                    modal.find('.modal-body #employeeid1').val(employeeid1);
                                                })
                                            </script>
                                            <script>
                                                $('#delete_employee'.$value->id).on('show.bs.modal', function(event) {
                                                    var button = $(event.relatedTarget)
                                                    var employeeid = button.data('employeeid')
                                                    var modal = $(this)
                                                    modal.find('.modal-body #employeeid').val(employeeid);
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    
@endsection
