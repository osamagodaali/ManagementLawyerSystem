@php
$setting = App\Models\Setting::find(1);
@endphp
<!-- main-sidebar -->
	<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
	<aside class="app-sidebar sidebar-scroll">
		<div class="main-sidebar-header active">
			<a class="desktop-logo logo-light active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('app/storage/assets/img/legal/logo/'.$setting->logo_img_name)}}" class="main-logo" alt="logo"></a>
			<a class="desktop-logo logo-dark active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('app/storage/assets/img/legal/logo/'.$setting->logo_img_name)}}" class="main-logo dark-theme" alt="logo"></a>
			<a class="logo-icon mobile-logo icon-light active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('app/storage/assets/img/legal/logo/'.$setting->logo_img_name)}}" class="logo-icon" alt="logo"></a>
			<a class="logo-icon mobile-logo icon-dark active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('app/storage/assets/img/legal/logo/'.$setting->logo_img_name)}}" class="logo-icon dark-theme" alt="logo"></a>
		</div>
		<div class="main-sidemenu">
			<div class="app-sidebar__user clearfix">
				<div class="dropdown user-pro-body">
					<div class="">
						<img alt="user-img" class="avatar avatar-xl brround" src="{{ URL::asset('app/storage/assets/img/legal/profile/' . $setting->profile_img_name) }}"><span class="avatar-status profile-status bg-green"></span>
					</div>
					<div class="user-info">
						<h4 class="font-weight-semibold mt-3 mb-0">{{Auth::user()->name }}</h4>
						<span class="mb-0 text-muted">{{Auth::user()->username }}</span>
					</div>
				</div>
			</div>
			<ul class="side-menu">

				<li class="slide">
					<a class="side-menu__item" href="{{ route('dashboard.index') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"  viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3"/><path d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg><span class="side-menu__label">لوحة التحكم</span></a>
				</li>

				<li class="slide">
					<a class="side-menu__item" href="{{ route('user.cases') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"  viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6.26 9L12 13.47 17.74 9 12 4.53z" opacity=".3"/><path d="M19.37 12.8l-7.38 5.74-7.37-5.73L3 14.07l9 7 9-7zM12 2L3 9l1.63 1.27L12 16l7.36-5.73L21 9l-9-7zm0 11.47L6.26 9 12 4.53 17.74 9 12 13.47z"/></svg><span class="side-menu__label">قائمة القضايا </span></a>
				</li>

				<li class="slide">
					<a class="side-menu__item" href="{{ route('user.cases_payments') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"  viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v4H5zm10 10h4v4h-4zM5 15h4v4H5zM16.66 4.52l-2.83 2.82 2.83 2.83 2.83-2.83z" opacity=".3"/><path d="M16.66 1.69L11 7.34 16.66 13l5.66-5.66-5.66-5.65zm-2.83 5.65l2.83-2.83 2.83 2.83-2.83 2.83-2.83-2.83zM3 3v8h8V3H3zm6 6H5V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm8-2v8h8v-8h-8zm6 6h-4v-4h4v4z"/></svg><span class="side-menu__label">قائمة المدفوعات </span></a>
				</li>

				<li class="slide">
					<a class="side-menu__item" href="{{ route('user.profile') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"  viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M10.9 19.91c.36.05.72.09 1.1.09 2.18 0 4.16-.88 5.61-2.3L14.89 13l-3.99 6.91zm-1.04-.21l2.71-4.7H4.59c.93 2.28 2.87 4.03 5.27 4.7zM8.54 12L5.7 7.09C4.64 8.45 4 10.15 4 12c0 .69.1 1.36.26 2h5.43l-1.15-2zm9.76 4.91C19.36 15.55 20 13.85 20 12c0-.69-.1-1.36-.26-2h-5.43l3.99 6.91zM13.73 9h5.68c-.93-2.28-2.88-4.04-5.28-4.7L11.42 9h2.31zm-3.46 0l2.83-4.92C12.74 4.03 12.37 4 12 4c-2.18 0-4.16.88-5.6 2.3L9.12 11l1.15-2z" opacity=".3"/><path d="M12 22c5.52 0 10-4.48 10-10 0-4.75-3.31-8.72-7.75-9.74l-.08-.04-.01.02C13.46 2.09 12.74 2 12 2 6.48 2 2 6.48 2 12s4.48 10 10 10zm0-2c-.38 0-.74-.04-1.1-.09L14.89 13l2.72 4.7C16.16 19.12 14.18 20 12 20zm8-8c0 1.85-.64 3.55-1.7 4.91l-4-6.91h5.43c.17.64.27 1.31.27 2zm-.59-3h-7.99l2.71-4.7c2.4.66 4.35 2.42 5.28 4.7zM12 4c.37 0 .74.03 1.1.08L10.27 9l-1.15 2L6.4 6.3C7.84 4.88 9.82 4 12 4zm-8 8c0-1.85.64-3.55 1.7-4.91L8.54 12l1.15 2H4.26C4.1 13.36 4 12.69 4 12zm6.27 3h2.3l-2.71 4.7c-2.4-.67-4.35-2.42-5.28-4.7h5.69z"/></svg><span class="side-menu__label">تعديل البروفايل</span></a>
				</li>

				<li class="slide">
					<a class="side-menu__item" href="{{ route('logout') }}"
									onclick="event.preventDefault();document.getElementById('logout-form').submit();"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"  viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" opacity=".3"/><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg><span class="side-menu__label">تسجيل خروج</span>
								</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
				</li>					
			</ul>
		</div>
	</aside>
<!-- main-sidebar -->
