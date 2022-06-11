@extends('layouts.master')
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الصلاحيات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة صلاحية </span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
                
@endsection
@section('content')
				<!-- row -->
				<div class="row">
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
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                    <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
						<div class="card">
                            <div class="card-header">
                                <span class="float-right">
                                    <a class="btn btn-primary" href="{{ route('permissions.index') }}">الصلاحيات</a>
                                </span>
                            </div>
                            <div class="card-body">
                                {!! Form::open(array('route' => 'permissions.store','method'=>'POST')) !!}
                                    <div class="form-group">
                                        <strong>اسم الصلاحية:</strong>
                                        {!! Form::text('name', null, array('placeholder' => 'اسم الصلاحية','class' => 'form-control')) !!}
                                    </div>
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
					</div>
                    <!--div-->
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection