@extends('layouts.master')
@section('title')
الاإعدادات العامة
@stop
@section('css')
<!-- Internal Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!--Internal  Datetimepicker-slider css -->
<link href="{{URL::asset('assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css')}}" rel="stylesheet"> 
<link href="{{URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css')}}" rel="stylesheet"> 
<link href="{{URL::asset('assets/plugins/pickerjs/picker.min.css')}}" rel="stylesheet"> 
<!---Internal Fileupload css-->
<link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
<!---Internal Fancy uploader css-->
<link href="{{URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />
<!-- Internal Spectrum-colorpicker css -->
<link href="{{URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css')}}" rel="stylesheet">
<!--Internal  Quill css -->
<link href="{{URL::asset('assets/plugins/quill/quill.snow.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/quill/quill.bubble.css')}}" rel="stylesheet">
<style>
    img {
        max-height: 214px;
    }
</style>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الاإعدادات العامة</h4>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
        <!-- row -->
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
                @can('الاعدادات')
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.update_setting') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                                {{ csrf_field() }} 
                                <div class="pd-30 pd-sm-40 bg-gray-200">
                                    <div class="row row-xs align-items-center mg-b-20">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">اسم المكتب  </label>
                                        </div>
                                        <div class="col-md-8 mg-t-5 mg-md-t-0">
                                            <input class="form-control" name="company_name" placeholder="ادخل اسم المكتب" type="text" value="{{ $setting->company_name }}">
                                        </div>
                                    </div>
                                    <div class="row row-xs align-items-center mg-b-20">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">اللوجو</label>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <input name="logo" type="file" class="dropify" data-height="200"  accept=".jpg,.jpeg,.bmp,.png,.gif" />
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <img src="{{URL::asset('app/storage/assets/img/legal/logo/'.$setting->logo_img_name)}}" class="logo-1" alt="logo">
                                        </div>
                                    </div>
                                    <div class="row row-xs align-items-center mg-b-20">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">ايقونة</label>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <input name="favicon" type="file" class="dropify" data-height="200"  accept=".jpg,.jpeg,.bmp,.png,.gif" />
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <img src="{{URL::asset('app/storage/assets/img/legal/favicon/'.$setting->favicon_img_name)}}" class="logo-1" alt="logo" height="">
                                        </div>
                                    </div>
                                    <div class="row row-xs align-items-center mg-b-20">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">صورة البروفايل</label>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <input name="profile" type="file" class="dropify" data-height="200"  accept=".jpg,.jpeg,.bmp,.png,.gif" />
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <img src="{{URL::asset('app/storage/assets/img/legal/profile/'.$setting->profile_img_name)}}" class="logo-1" alt="logo">
                                        </div>
                                    </div>
                                    {{-- <div class="row row-xs align-items-center mg-b-20">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">صورة صفحة دخول العميل</label>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <input name="user_login" type="file" class="dropify" data-height="200"  accept=".jpg,.jpeg,.bmp,.png,.gif" />
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <img src="{{URL::asset('app/storage/assets/img/legal/user_login/'.$setting->login_user_img_name)}}" class="logo-1" alt="logo">
                                        </div>
                                    </div> --}}
                                    <div class="row row-xs align-items-center mg-b-20">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">صورة صفحة دخول الادمن</label>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <input name="admin_login" type="file" class="dropify" data-height="200"  accept=".jpg,.jpeg,.bmp,.png,.gif" />
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <img src="{{URL::asset('app/storage/assets/img/legal/admin_login/'.$setting->login_admin_img_name)}}" class="logo-1" alt="logo">
                                        </div>
                                    </div>
                                    <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تحديث </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
        <!-- /row -->
        </div>
    </div>
    <!-- Container closed -->
@endsection
@section('js')
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
<!-- <script src="{{URL::asset('assets/plugins/pickerjs/picker.min.js')}}"></script> -->
<!-- Internal form-elements js -->
<script src="{{URL::asset('assets/js/form-elements.js')}}"></script>
<!--Internal Fileuploads js-->
<script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>
<!--Internal Fancy uploader js-->
<script src="{{URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fancyuploder/fancy-uploader.js')}}"></script>
<!--Internal quill js -->
<script src="{{URL::asset('assets/plugins/quill/quill.min.js')}}"></script>
<!-- Internal Form-editor js -->
<script src="{{URL::asset('assets/js/form-editor.js')}}"></script>
@endsection
