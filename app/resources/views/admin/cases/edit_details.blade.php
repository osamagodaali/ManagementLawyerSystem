@extends('layouts.master')
@section('title')
تعديل ل سير القضية
@stop
@section('css')
<link href="{{URL::asset('assets/plugins/pickerjs/picker.min.css')}}" rel="stylesheet"> 
<!---Internal Fileupload css-->
<link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">القضايا</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/  تعديل ل سير القضية  </span>
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
                @can('تعديل لسير القضية')
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.update_case_details') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" name="_method" value="PATCH">
                                {{ csrf_field() }} 
                                <div class="main-content-label mg-b-5">إضافة ل سير القضية  </div>
                                <br>
                                <input type="hidden" name="caseid" value="<?php echo $case->id; ?>" />
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">القرارات على القضية : <span class="tx-danger">*</span></label>
                                            <textarea name="case_decisions" class="form-control" required="" ><?php echo $case->case_decisions; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">الإجراء المقترح : <span class="tx-danger">*</span></label>
                                            <textarea name="suggested_action" class="form-control" required="" ><?php echo $case->suggested_action; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">ملاحظات : <span class="tx-danger">*</span></label>
                                            <textarea name="details" class="form-control" required="" ><?php echo $case->details; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">موعد الجلسة القادم : <span class="tx-danger">*</span></label>
                                            <input class="form-control fc-datepicker" name="nextfollowdate" placeholder="MM/DD/YYYY" type="text" value="<?php echo $case->nextfollowdate; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group mg-b-0">
                                        <label class="form-label">المرفقات : </label>
                                        <input name="file" type="file" class="dropify" data-height="200"  accept=".pdf" />
                                    </div>
                                </div>
                                <br>
                                <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تحديث </button>
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
