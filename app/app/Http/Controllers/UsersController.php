<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Users_wallet;
use App\Models\Users_wallet_transaction;
use App\Models\AdminsActivities;
use App\Models\Admin;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:قائمة العملاء', ['only' => ['index','show']]);
        $this->middleware('permission:انشاء عميل', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل عميل', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف عميل', ['only' => ['destroy']]);
        $this->middleware('permission:تغيير كلمة مرور عميل', ['only' => ['change_password']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index' , compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
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
            'name'      => 'required', 'string', 'max:255',
            // 'mobile'    => 'required|numeric|unique:users',
            // 'email'     => 'required', 'email', 'max:255', 'unique:users',
        ],[
            'name.required' => 'برجاء ادخال الاسم ',
            // 'mobile.unique'   => 'برجاء ادخال رقم الهاتف  غير مكرر',
            // 'mobile.numeric'  => 'رقم الهاتف يجب ان يكون رقم ',
            // 'mobile.required'  => 'برجاءادخال رقم الهاتف ',
            // 'email.unique'   => 'برجاء ادخال بريد الكتروني  غير مكرر',
            // 'email.required'  => 'برجاءادخال  بريد الكتروني',
        ]);

        $password = Str::random(8);
        
        $user = User::create([
            'name'              => $request->name,
            'mobile'            => $request->mobile,
            'email'             => $request->email,
            'nationalid'        => $request->nationalid, 
            'address'           => $request->address,
            'gender'            => $request->gender,
            'password'          => Hash::make($password),
            'random_password'   => $password,
            'status'            => 0,
            'password_status'   => 1,
        ]);

        // $userid = User::latest()->first()->id;

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة عميل جديد - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('users.index')
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
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::find($id);
        return view('admin.users.edit', compact('users'));
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
            // 'mobile'    => 'required|numeric|unique:users,mobile,'.$id,
            // 'email'     => 'required', 'string', 'email', 'max:255',
        ],[
            'name.required' => 'برجاء ادخال الاسم ',
            // 'mobile.unique'   => 'برجاء ادخال رقم الهاتف  غير مكرر',
            // 'mobile.numeric'  => 'رقم الهاتف يجب ان يكون رقم ',
            // 'mobile.required'  => 'برجاءادخال رقم الهاتف ',
            // 'email.unique'   => 'برجاء ادخال بريد الكتروني  غير مكرر',
            // 'email.required'  => 'برجاءادخال  بريد الكتروني',
        ]);
    
        $user = User::find($id);
        $user->name         = $request->input('name');
        $user->mobile       = $request->input('mobile');
        $user->email        = $request->input('email');
        $user->nationalid   = $request->input('nationalid');
        $user->address      = $request->input('address');
        $user->gender       = $request->input('gender');
        $user->save();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => ' تعديل بيانات العميل - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('users.index')
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
        $user = User::find($id);
        $user->delete();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'حذف العميل - '. $user->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('users.index')
            ->with('success', 'تم الحذف بنجاح');
    } 

    public function change_password(Request $request ){
        $id = $request->userid;
        $password = Str::random(8);

        $user_data  = User::findOrFail($id);
        $user_data->update([
            'password'          => Hash::make($password),
            'random_password'   => $password,
            'password_status'   => 1,
        ]);
        
        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تغيير كلمة المرور للعميل - '. $user_data->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('users.show' ,  $id)
            ->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}
