<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Admin;
use App\Models\AdminsActivities;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:قائمة الموظفين', ['only' => ['index', 'show']]);
        $this->middleware('permission:انشاء موظف', ['only' => ['create', 'store']]);
        $this->middleware('permission:تعديل موظف', ['only' => ['edit', 'update']]);
        $this->middleware('permission:حذف موظف', ['only' => ['destroy']]);
        $this->middleware('permission:تغيير كلمة مرور موظف', ['only' => ['change_password']]);
        $this->middleware('permission:مشاهدة نشاط الموظف', ['only' => ['show_activity', 'show_all_activities']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return auth()->user()->roles->first()->name ;
        $employees = Admin::all();
        return view('admin.employees.index' , compact('employees'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.employees.create' , compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'mobile'    => 'required|numeric|unique:admins',
            'email'     => 'required|string|email|max:255|unique:admins',
        ],[
            'name.required' => 'برجاء ادخال الاسم ',
            'mobile.unique'   => 'برجاء ادخال رقم الهاتف  غير مكرر',
            'mobile.required'  => 'برجاءادخال رقم الهاتف ',
            'mobile.numeric'  => 'رقم الهاتف يجب ان يكون رقم ',
            'email.unique'   => 'برجاء ادخال بريد الكتروني  غير مكرر',
            'email.required'  => 'برجاءادخال  بريد الكتروني',
        ]);

        $password = Str::random(8);

        $admin = Admin::create([
            'name'              => $request->name,
            'mobile'            => $request->mobile,
            'email'             => $request->email,
            'password'          => Hash::make($password),
            'random_password'   => $password,
            'cases_availabe'    => $request->cases_availabe,
            'status'            => 0,
            'password_status'   => 1,
        ]);
        $admin->assignRole($request->input('roles'));
        $id = Admin::latest()->first()->id;

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة موظف جديد - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('employees.show' ,  $id)
            ->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Admin::find($id);
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Admin::find($id);
        $roles = Role::all();
        $followsBy = DB::table('model_has_roles')
            ->where('model_has_roles.model_id', $id)
            ->pluck('model_has_roles.role_id', 'model_has_roles.role_id')
            ->all();
            // return $followsBy;   
        return view('admin.employees.edit', compact('employee','roles' ,'followsBy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required','string', 'max:255',
            'mobile'    => 'required|numeric|unique:admins,mobile,'.$id,
            'email'     => 'required', 'string', 'email', 'max:255',
        ],[
            'name.required' => 'برجاء ادخال الاسم ',
            'mobile.unique'   => 'برجاء ادخال رقم الهاتف  غير مكرر',
            'mobile.required'  => 'برجاءادخال رقم الهاتف ',
            'mobile.numeric'  => 'رقم الهاتف يجب ان يكون رقم ',
            'email.unique'   => 'برجاء ادخال بريد الكتروني  غير مكرر',
            'email.required'  => 'برجاءادخال  بريد الكتروني',
        ]);
    
        $employee = Admin::find($id);
        $employee->name     = $request->input('name');
        $employee->mobile   = $request->input('mobile');
        $employee->email    = $request->input('email');
        $employee->cases_availabe    = $request->input('cases_availabe');
        $employee->save();

        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $employee->assignRole($request->input('roles'));

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات موظف - '. $employee->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));
        return redirect()->route('employees.index')
            ->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        $employee = Admin::find($id);
        $employee->delete();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'حذف بيانات موظف - '. $employee->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));
        return redirect()->route('employees.index')
            ->with('success', 'تم الحذف بنجاح');
    }

    public function change_password(Request $request ){
        $id = $request->employeeid1;
        $password = Str::random(8);

        $admin_data  = Admin::findOrFail($id);
        $admin_data->update([
            'password'          => Hash::make($password),
            'random_password'   => $password,
            'password_status'   => 1,
        ]);

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تغيير كلمة مرور للموظف - '. $admin_data->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));
        return redirect()->route('employees.show' ,  $id)
            ->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    public function showForgotForm(){
        return view('admin.forgot-password');
    }

    public function sendResetLink(Request $request){
         $request->validate([
             'email'=>'required|email|exists:admins,email'
         ]);

         $token = \Str::random(64);
         \DB::table('password_resets')->insert([
               'email'=>$request->email,
               'token'=>$token,
               'created_at'=>Carbon::now(),
         ]);
         
         $action_link = route('admin.password.reset',['token'=>$token,'email'=>$request->email]);
         $body = "We are received a request to reset the password for <b>Your app Name </b> account associated with ".$request->email.". You can reset your password by clicking the link below";

        \Mail::send('admin.email-forgot',['action_link'=>$action_link,'body'=>$body], function($message) use ($request){
              $message->from('email@servicescenters.online','Your App Name');
              $message->to($request->email,'Your name')
                      ->subject('Reset Password');
        });

        return back()->with('success', 'We have e-mailed your password reset link!');
    }

    public function showResetForm(Request $request, $token = null){
        return view('admin.reset-password')->with(['token'=>$token,'email'=>$request->email]);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email'=>'required|email|exists:admins,email',
            'password'=>'required|min:5|confirmed',
            'password_confirmation'=>'required',
        ]);

        $check_token = \DB::table('password_resets')->where([
            'email'=>$request->email,
            'token'=>$request->token,
        ])->first();

        if(!$check_token){
            return back()->withInput()->with('fail', 'Invalid token');
        }else{

            Admin::where('email', $request->email)->update([
                'password'=>\Hash::make($request->password)
            ]);

            \DB::table('password_resets')->where([
                'email'=>$request->email
            ])->delete();

            return redirect()->route('admin.login')->with('info', 'Your password has been changed! You can login with new password')->with('verifiedEmail', $request->email);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_activity($id)
    {
        $activity = AdminsActivities::join('admins', 'admins.id', '=', 'admins_activities.admin_id')
                                    ->where('admin_id' , $id)
                                    ->get(['admins_activities.*' , 'admins.name']);
        return view('admin.employees.show_activity', compact('activity'));
    }

    public function show_all_activities(){
        $activities = AdminsActivities::join('admins', 'admins.id', '=', 'admins_activities.admin_id')
                                    ->get(['admins_activities.*' , 'admins.name']);
        return view('admin.employees.show_all_activities', compact('activities'));
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function maintemplate($id)
    {
        // return $id;
        if(view()->exists($id)){
            return view($id);
        }
        else
        {
            return view('405');
        }
    }

}
