<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Setting;
use App\Models\Admin;
use App\Models\CasesType;
use App\Models\User;
use App\Models\AdminsActivities;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role; 
use Carbon\Carbon;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $admin_id = Auth::user()->id;
        $admin_data = Admin::find($admin_id);
        $now =  Carbon::now()->format('m/d/Y');
        $next_1_day_date =  Carbon::now()->addDays(1)->format('m/d/Y');
        $next_2_day_date =  Carbon::now()->addDays(2)->format('m/d/Y');

        if($admin_data->password_status == 0){
            $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
            if($cases_availabe == 0){
                $all_cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")->where('admin_has_cases_roles.admin_id' , $admin_id)->count();
                $new_cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")->where('admin_has_cases_roles.admin_id' , $admin_id)->where('cases.case_status' , '=', 1)->count();
                $current_cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")->where('admin_has_cases_roles.admin_id' , $admin_id)->where('cases.case_status' , '=', 2)->count();
                $finished_cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")->where('admin_has_cases_roles.admin_id' , $admin_id)->where('cases.case_status' , '=', 3)->count();               
                
                $next_cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                                    ->join("case_details","case_details.caseid" ,"=","cases.id")
                                    ->join("users","users.id" ,"=","cases.userid")
                                    ->join("branches","branches.id" ,"=","cases.brancheid")
                                    ->join("courts","courts.id" ,"=","cases.courtid")
                                    ->join("judicial_chambers","judicial_chambers.id" ,"=","cases.judicialchamberid")
                                    ->join("cases_types","cases_types.id" ,"=","cases.case_type")
                                    ->where("case_details.nextfollowdate","=" , $now)
                                    ->orWhere("case_details.nextfollowdate","=" , $next_1_day_date)
                                    ->orWhere("case_details.nextfollowdate","=" , $next_2_day_date)
                                    ->get(['cases.*','case_details.nextfollowdate','users.name as username','branches.name as branchename','courts.name as courtname','judicial_chambers.name as judicial_chamber_name','cases_types.name as case_type_name']);
                $last_5_cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")->where('admin_has_cases_roles.admin_id' , $admin_id)->latest()->take(5)->get('cases.*');
            }elseif($cases_availabe == 1){
                $all_cases = Cases::all()->count();
                $new_cases = Cases::where('case_status' , '=', 1)->count();
                $current_cases = Cases::where('case_status' , '=', 2)->count();
                $finished_cases = Cases::where('case_status' , '=', 3)->count();

                $next_cases = Cases::join("case_details","case_details.caseid" ,"=","cases.id")
                                    ->join("users","users.id" ,"=","cases.userid")
                                    ->join("branches","branches.id" ,"=","cases.brancheid")
                                    ->join("courts","courts.id" ,"=","cases.courtid")
                                    ->join("judicial_chambers","judicial_chambers.id" ,"=","cases.judicialchamberid")
                                    ->join("cases_types","cases_types.id" ,"=","cases.case_type")
                                    ->where("case_details.nextfollowdate","=" , $now)
                                    ->orWhere("case_details.nextfollowdate","=" , $next_1_day_date)
                                    ->orWhere("case_details.nextfollowdate","=" , $next_2_day_date)
                                    ->get(['cases.*','case_details.nextfollowdate','users.name as username','branches.name as branchename','courts.name as courtname','judicial_chambers.name as judicial_chamber_name','cases_types.name as case_type_name']);
                $last_5_cases = Cases::latest()->take(5)->get();
            }

            $casesTypes = CasesType::all(); 
            $last_5_users = User::latest()->take(5)->get();
            return view('admin.dashboard' , compact('all_cases' , 'new_cases', 'current_cases', 'finished_cases','casesTypes','last_5_cases','next_cases','last_5_users'));
        }elseif($admin_data->password_status == 1){
            return view('admin.change_password');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $id = 1;
        $setting = Setting::find($id);
        return view('admin.setting', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = 1;
        $validated = $request->validate([
            'company_name'  => 'required',
            
        ],[
            'company_name.required' => 'برجاء اختيار العميل  ',
        ]);

        $setting = Setting::find($id);

        if ($request->hasFile('logo')) {
            $path = storage_path('assets/img/legal/logo');
            $logo_img_name = $this->save_image($request->file('logo') , $path);
        }else{
            $logo_img_name = $setting->logo_img_name;
        }
        if ($request->hasFile('favicon')) {
            $path = storage_path('assets/img/legal/favicon');
            $favicon_img_name = $this->save_image($request->file('favicon') , $path);
        }else{
            $favicon_img_name = $setting->favicon_img_name;
        }
        if ($request->hasFile('profile')) {
            $path = storage_path('assets/img/legal/profile');
            $profile_img_name = $this->save_image($request->file('profile') , $path);
        }else{
            $profile_img_name = $setting->profile_img_name;
        }
        // if ($request->hasFile('user_login')) {
        //     $path = storage_path('assets/img/legal/user_login');
        //     $login_user_img_name = $this->save_image($request->file('user_login') , $path);
        // }else{
        //     $login_user_img_name = $setting->login_user_img_name;
        // }
        if ($request->hasFile('admin_login')) {
            $path = storage_path('assets/img/legal/admin_login');
            $login_admin_img_name = $this->save_image($request->file('admin_login') , $path);
        }else{
            $login_admin_img_name = $setting->login_admin_img_name;
        }
        
        
        $setting->company_name          = $request->company_name;
        $setting->logo_img_name         = $logo_img_name;
        $setting->favicon_img_name      = $favicon_img_name;
        $setting->profile_img_name      = $profile_img_name;
        // $setting->login_user_img_name   = $login_user_img_name;
        $setting->login_admin_img_name  = $login_admin_img_name;
        $setting->save();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات مكتب المحاماه ',
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('admin.setting')->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function update_password(Request $request){
        $this->validate($request, [
            'password'  => 'required', 'confirmed', Rules\Password::defaults(), 
        ]);
        
        $admin_id    = Auth::user()->id;
        $admin_data  = Admin::findOrFail($admin_id); 
        $admin_data->update([
            'password'          => Hash::make($request->password),
            'remember_token'    => Str::random(60), 
            'password_status'   => 0 ,
        ]);

        Auth::guard('webadmin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin');
    }

    public function save_image($file , $path){
        $fileName = $file->getClientOriginalName();
        
        $file->move($path, $fileName);
        return  $fileName;
    }

    public function profile(){
        $admin_id = Auth::user()->id;
        $profiledata = Admin::find($admin_id);
        return view('admin.profile', compact('profiledata'));
    }

    public function update_profile(Request $request){
        $admin_id = Auth::user()->id;
        $admin = Admin::find($admin_id);
        $validated = $request->validate([
            'name'      => 'required',
            'mobile'    => 'required',
            'email'     => 'required',
        ],[
            'name.required'     => 'برجاء ادخال الاسم بالكامل',
            'mobile.required'   => 'برجاء ادخال رقم الهاتف ',
            'email.required'    => 'برجاء ادخال البريد الالكتروني ',
        ]);

        if(!empty($request->password)){
            if (!(Hash::check($request->old_password, Auth::user()->password))) {
                return redirect()->back()->with('error','كلمة المرور القديمة غير صحيحة ');
            }
    
            if(strcmp($request->old_password , $request->password ) == 0){
                return redirect()->back()->with('error','كلمة المرور الجديد نفس كلمة المرور الحالية');
            }
    
            $validatedData = $request->validate([
                'password'    => 'required|string|min:8|confirmed',
            ],[
                'password.min'     => 'كلمة المرور يجب الا تقل عن 8 حروف وارقام',
                'password.confirmed'   => 'كلمة المرور غير متطابقة مع تأكيد كلمة المرور ',
            ]);
            $admin_password = Hash::make($request->password) ;
        }else{
            $admin_password = $admin->password;
        }
        
        $admin->name    = $request->name;
        $admin->mobile  = $request->mobile;
        $admin->email   = $request->email;
        $admin->password= $admin_password;
        $admin->save();
        
        return redirect()->route('admin.profile')->with('success', 'تم التحديث بنجاح');
    }


    public function getNotifications(){
        return [
            'read'      => auth()->user()->readNotifications ,
            'unread'    => auth()->user()->unreadNotifications  ,
            'usertype'  => auth()->user()->roles->first()->name  
        ];
    }

    public function markAsRead(Request $request){
        return auth()->user()->notifications->where('id', $request->id)->markAsRead();
    }

    public function markAsReadAndRedirect($id){
        $notification = auth()->user()->notifications->where('id', $id)->first();
        $notification->markAsRead();
        return redirect()->back();
    }

    public function markAsReadAllAndRedirect(){
        $notifications = auth()->user()->notifications;
        foreach($notifications as $notification){
            $notification->markAsRead($notification['id']);
        }
        return redirect()->back();
    }

    public function showAllNotifications(){
        $all_notifications = auth()->user()->notifications;
        foreach($all_notifications as $notification){
            $notification->markAsRead($notification['id']);
        }
        return view('admin.showAllNotifications', compact('all_notifications'));
    }
}
