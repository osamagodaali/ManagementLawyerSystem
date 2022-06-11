<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CasesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\RevenuesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\CasesTypeController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\JudicialChamberController;

use App\Http\Controllers\Clients\ClientsDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('admin.login'); 
    return redirect('admin/login'); 
});
Route::get('/login', function () {
    // return view('admin.login');
    return redirect('admin/login'); 
});
Route::get('/admin', function () {
    // return view('admin.login');
    return redirect('admin/login'); 
});

// admin login routes
Route::get('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'handleLogin'])->name('admin.handleLogin');
Route::get('admin/logout', [AdminAuthController::class, 'index'])->name('admin.logout');
// dashboard prifex routes
Route::group( [ 'prefix' => 'admin' ], function()
{
    // dashboard
    // Route::get('/', function () {
    //     return view('admin.login');
    // });
    Route::resource('dashboard', DashboardController::class)->middleware(['auth:webadmin']);
    Route::post('update_password' , [DashboardController::class, 'update_password'])->middleware(['auth:webadmin'])->name('admin.update_password');
    Route::get('setting' , [DashboardController::class, 'edit'])->middleware(['auth:webadmin'])->name('admin.setting');
    Route::post('setting' , [DashboardController::class, 'update'])->middleware(['auth:webadmin'])->name('admin.update_setting');
    Route::get('profile' , [DashboardController::class, 'profile'])->middleware(['auth:webadmin'])->name('admin.profile');
    Route::post('profile' , [DashboardController::class, 'update_profile'])->middleware(['auth:webadmin'])->name('admin.update_profile');
    // notification
    Route::any('notification/get',[DashboardController::class, 'getNotifications'])->middleware(['auth:webadmin'])->name('admin.getNotifications');
    Route::any('notification/read',[DashboardController::class, 'markAsRead'])->middleware(['auth:webadmin'])->name('admin.markAsRead');
    Route::any('notification/read/{id}',[DashboardController::class, 'markAsReadAndRedirect'])->middleware(['auth:webadmin'])->name('admin.markAsReadAndRedirect');
    Route::any('notification/readAll',[DashboardController::class, 'markAsReadAllAndRedirect'])->middleware(['auth:webadmin'])->name('admin.markAsReadAllAndRedirect');
    Route::any('notification/showAll',[DashboardController::class, 'showAllNotifications'])->middleware(['auth:webadmin'])->name('admin.showAllNotifications');
    // investors
    Route::resource('users', UsersController::class)->middleware(['auth:webadmin']); 
    Route::get('user_data' , [UsersController::class, 'user_data'])->middleware(['auth:webadmin'])->name('admin.user_data');
    Route::post('user_change_password' , [UsersController::class, 'change_password'])->middleware(['auth:webadmin'])->name('admin.user_change_password');
    // roles
    Route::resource('cases', CasesController::class)->middleware(['auth:webadmin']);
    Route::get('new_cases' , [CasesController::class, 'new_cases'])->middleware(['auth:webadmin'])->name('admin.new_cases');
    Route::get('current_cases' , [CasesController::class, 'current_cases'])->middleware(['auth:webadmin'])->name('admin.current_cases');
    Route::get('finished_cases' , [CasesController::class, 'finished_cases'])->middleware(['auth:webadmin'])->name('admin.finished_cases');
    Route::get('processes_cases' , [CasesController::class, 'processes_cases'])->middleware(['auth:webadmin'])->name('admin.processes_cases');
    Route::get('collections_cases' , [CasesController::class, 'collections_cases'])->middleware(['auth:webadmin'])->name('admin.collections_cases');
    Route::get('add_case_details_form/{case_id}' , [CasesController::class, 'add_case_details_form'])->middleware(['auth:webadmin'])->name('admin.add_case_details_form');
    Route::post('add_case_details' , [CasesController::class, 'add_case_details'])->middleware(['auth:webadmin'])->name('admin.add_case_details');
    Route::get('edit_case_details_form/{case_id}' , [CasesController::class, 'edit_case_details_form'])->middleware(['auth:webadmin'])->name('admin.edit_case_details_form');
    Route::patch('update_case_details' , [CasesController::class, 'update_case_details'])->middleware(['auth:webadmin'])->name('admin.update_case_details');
    Route::post('case_details_destroy', [CasesController::class,'case_details_destroy'])->middleware(['auth:webadmin'])->name('admin.case_details_destroy');
    Route::get('view_file/{file_id}', [CasesController::class,'view_case_details_file'])->middleware(['auth:webadmin'])->name('admin.view_file');
    Route::post('file_destroy', [CasesController::class,'file_destroy'])->middleware(['auth:webadmin'])->name('admin.file_destroy');
    Route::patch('update_case_status' , [CasesController::class, 'update_case_status'])->middleware(['auth:webadmin'])->name('admin.update_case_status');
    Route::post('casesfilter', [CasesController::class,'casesfilter'])->middleware(['auth:webadmin'])->name('admin.casesfilter');
    Route::get('case_transactions/{case_id}', [CasesController::class,'case_transactions'])->middleware(['auth:webadmin'])->name('admin.case_transactions');
    Route::post('newcasesfilter', [CasesController::class,'newcasesfilter'])->middleware(['auth:webadmin'])->name('admin.newcasesfilter');
    Route::post('currentcasesfilter', [CasesController::class,'currentcasesfilter'])->middleware(['auth:webadmin'])->name('admin.currentcasesfilter');
    Route::post('finishedcasesfilter', [CasesController::class,'finishedcasesfilter'])->middleware(['auth:webadmin'])->name('admin.finishedcasesfilter');
    Route::post('processescasesfilter', [CasesController::class,'processescasesfilter'])->middleware(['auth:webadmin'])->name('admin.processescasesfilter');
    Route::post('collectionscasesfilter', [CasesController::class,'collectionscasesfilter'])->middleware(['auth:webadmin'])->name('admin.collectionscasesfilter');
    Route::get('emplyee_cases/{employee_id}' , [CasesController::class, 'emplyee_cases'])->middleware(['auth:webadmin'])->name('admin.emplyee_cases');
    // emloyees
    Route::resource('employees', AdminController::class)->middleware(['auth:webadmin']);
    Route::get('employee_data' , [AdminController::class, 'employee_data'])->middleware(['auth:webadmin'])->name('admin.employee_data');
    Route::post('change_password' , [AdminController::class, 'change_password'])->middleware(['auth:webadmin'])->name('admin.change_password');
    Route::get('/password/forgot',[AdminController::class,'showForgotForm'])->name('admin.password.request');
    Route::post('/password/forgot',[AdminController::class,'sendResetLink'])->name('admin.password.email');
    Route::get('/password/reset/{token}',[AdminController::class,'showResetForm'])->name('admin.password.reset');
    Route::post('/password/reset',[AdminController::class,'resetPassword'])->name('admin.password.update');
    Route::get('show_activity/{employee_id}' , [AdminController::class, 'show_activity'])->middleware(['auth:webadmin'])->name('admin.show_activity');
    Route::get('show_all_activities' , [AdminController::class, 'show_all_activities'])->middleware(['auth:webadmin'])->name('admin.show_all_activities');
    
    // roles
    Route::resource('roles', RoleController::class)->middleware(['auth:webadmin']);
    // permissions
    Route::resource('permissions', PermissionController::class)->middleware(['auth:webadmin']);
    // accounting
    Route::resource('revenues', RevenuesController::class)->middleware(['auth:webadmin']);
    Route::resource('expenses', ExpensesController::class)->middleware(['auth:webadmin']);
    // Branches
    Route::resource('branches', BranchesController::class)->middleware(['auth:webadmin']);
    // CasesType
    Route::resource('casestypes', CasesTypeController::class)->middleware(['auth:webadmin']);
    // Court
    Route::resource('courts', CourtController::class)->middleware(['auth:webadmin']);
    // JudicialChamber
    Route::resource('judicialchambers', JudicialChamberController::class)->middleware(['auth:webadmin']);
    Route::post('getjudicialchambers', [JudicialChamberController::class, 'getJudicialChamber'])->name('admin.getjudicialchambers');
    Route::post('logout', [AdminAuthController::class, 'destroy'])->middleware('auth:webadmin')->name('admin.logout');
    
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// users routes
// dashboard prifex routes
Route::group( [ 'prefix' => 'user' ], function()
{
    // dashboard
    Route::resource('dashboard', ClientsDashboardController::class)->middleware(['auth']);
    Route::post('change_password' , [ClientsDashboardController::class, 'change_password'])->middleware(['auth'])->name('user.change_password');
    Route::get('account' , [ClientsDashboardController::class, 'account'])->middleware(['auth'])->name('user.account');
    Route::get('profile' , [ClientsDashboardController::class, 'profile'])->middleware(['auth'])->name('user.profile');
    Route::post('update_profile' , [ClientsDashboardController::class, 'update_profile'])->middleware(['auth'])->name('user.update_profile');
    Route::get('cases' , [ClientsDashboardController::class, 'cases'])->middleware(['auth'])->name('user.cases');
    Route::get('cases_details/{id}' , [ClientsDashboardController::class, 'cases_details'])->middleware(['auth'])->name('user.cases_details');
    Route::get('cases_payments' , [ClientsDashboardController::class, 'cases_payments'])->middleware(['auth'])->name('user.cases_payments');
});

Route::get('/maintemplate/{page}', [AdminController::class, 'maintemplate']);
require __DIR__.'/auth.php';
