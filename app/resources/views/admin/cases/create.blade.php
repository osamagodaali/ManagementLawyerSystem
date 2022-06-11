@extends('layouts.master')
@section('title')
إضافة قضية جديدة
@stop
@section('css')
<!-- Internal Select2 css -->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/pickerjs/picker.min.css')}}" rel="stylesheet"> 
<!---Internal Fileupload css-->
<link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">القضايا</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/  إضافة قضية جديدة</span>
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
                @can('انشاء قضية')
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('cases.store') }}" method="post" enctype="multipart/form-data" autocomplete="off" data-parsley-validate="" >
                                {{ csrf_field() }} 
                                <div class="main-content-label mg-b-5">إضافة قضية  جديدة</div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">العميل : <span class="tx-danger">*</span></label>
                                            <select name="userid" class="form-control select2" required="" >
                                                <option value="">اختر العميل </option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{$user->name}} </option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">الفرع : <span class="tx-danger">*</span></label>
                                            <select name="brancheid" class="form-control select2" required="" >
                                                <option value="">اختر الفرع </option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{$branch->name}} </option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">المحكمة : <span class="tx-danger">*</span></label>
                                            <select name="courtid" class="form-control select2 " required="" id="courts-dropdown" >
                                                <option value="">اختر المحكمة </option>
                                                    @foreach ($courts as $court)
                                                        <option value="{{ $court->id }}">{{$court->name}} </option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">الدائرة القضائية : <span class="tx-danger">*</span></label>
                                            <select name="judicialchamberid" class="form-control select2" required="" id="JudicialChambers-dropdown" >
                                                <option value="">اختر الدائرة القضائية </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label"> نوع القضية : <span class="tx-danger">*</span></label>
                                            <select name="case_type" class="form-control select2" required="" >
                                                <option value="">اختر نوع القضية  </option>
                                                    @foreach ($casesType as $cType)
                                                        <option value="{{ $cType->id }}">{{$cType->name}} </option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">رقم القضية: <span class="tx-danger">*</span></label>
                                            <input name="case_number" class="form-control" type="text" required="" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">الصفة: <span class="tx-danger">*</span></label>
                                            <input name="title" class="form-control" type="text" required="" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">ملاحظات : <span class="tx-danger">*</span></label>
                                            <textarea name="details" class="form-control" required="" ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">مبلغ المطالبة: <span class="tx-danger">*</span></label>
                                            <input name="case_value" class="form-control" type="number" required="" >
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">حالة الدفع: <span class="tx-danger">*</span></label>
                                            <select name="payment_status" class="form-control" required="" >
                                                <option value="0"> لم يتم الدفع </option>
                                                <option value="1"> دفع جزئي </option>
                                                <option value="2"> دفع كامل </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group mg-b-0">
                                            <label class="form-label">متابعة القضية بواسطة: <span class="tx-danger">*</span></label>
                                            <select name="followby[]" class="form-control select2" multiple="multiple" required="" >
                                                <option value="">اختر المحامي </option>
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($employees as $employee)
                                                    @php
                                                        $i++;
                                                    @endphp
                                                    <option value="{{ $employee->id }}">{{$employee->name}} </option>
                                                @endforeach
                                            </select>
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
                                <button class="btn btn-main-primary pd-x-30 mg-r-5 mg-t-5">تسجيل جديد</button>
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
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
<!-- Ionicons js -->
<script src="{{URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>
<!-- Internal form-elements js -->
<script src="{{URL::asset('assets/js/form-elements.js')}}"></script>
<!--Internal  Parsley.min js -->
<script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
<!-- Internal Form-validation js -->
<script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
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
<!--Internal Ion.rangeSlider.min js -->
<script src="{{URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<!--Internal Fileuploads js-->
<script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>
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
