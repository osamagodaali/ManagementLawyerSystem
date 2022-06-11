@extends('layouts.master')
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الصلاحيات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفاصيل الصلاحية </span>
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
						<div class="card mg-b-20">
							<div class="justify-content-center">
                                @if (\Session::has('success'))
                                    <div class="alert alert-success">
                                        <p>{{ \Session::get('success') }}</p>
                                    </div>
                                @endif
                                <div class="card">
                                    <div class="card-header">
                                        @can('role-create')
                                            <span class="float-right">
                                                <a class="btn btn-primary" href="{{ route('permissions.index') }}">الصلاحيات</a>
                                            </span>
                                        @endcan
                                    </div>
                                    <div class="card-body">
                                        <div class="lead">
                                            <strong>الاسم:</strong>
                                            {{ $permission->name }}
                                        </div>
                                    </div>
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
