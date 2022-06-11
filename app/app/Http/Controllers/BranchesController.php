<?php

namespace App\Http\Controllers;

use App\Models\Branche;
use App\Models\AdminsActivities;
use App\Models\Admin;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchesController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        // $this->middleware('permission:كل الصلاحيات الفروع', ['only' => ['index','create','store','show','edit','update', 'destroy']]);
        $this->middleware('permission:قائمة الفروع', ['only' => ['index', 'show']]);
        $this->middleware('permission:انشاء فرع', ['only' => ['create', 'store']]);
        $this->middleware('permission:تعديل فرع', ['only' => ['edit', 'update']]);
        $this->middleware('permission:حذف فرع', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branche::all();
        return view('admin.branches.index' , compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.branches.create');
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
            'mobile'    => 'required', 
            'address'   => 'required', 'string', 'max:255',
        ],[
            'name.required' => 'برجاء ادخال الاسم ',
            'mobile.required'  => 'برجاءادخال رقم الهاتف ',
            'address.required'  => 'برجاءادخال  العنوان ',
        ]);
        
        $Branche = Branche::create([
            'name'              => $request->name,
            'mobile'            => $request->mobile,
            'address'           => $request->address,
        ]);

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة فرع جديد - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('branches.index')
        ->with('success', 'تم إضافة الفرع بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branche  $branche
     * @return \Illuminate\Http\Response
     */
    public function show(Branche $branche)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branche  $branche
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branche = Branche::find($id);
        return view('admin.branches.edit', compact('branche'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branche  $branche
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request , Branche $branche , $id)
    {
        $this->validate($request, [
            'name'      => 'required',
            'mobile'    => 'required',
            'address'   => 'required',
        ]);
    
        // $id      = $request->input('brancheid');
        $branche = Branche::find($id);
        $branche->name      = $request->input('name');
        $branche->mobile    = $request->input('mobile');
        $branche->address   = $request->input('address');
        $branche->save();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات الفرع  - '. $request->input('name'),
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('branches.index')
            ->with('success', 'تم تحديث الفرع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branche  $branche
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branche = Branche::find($id);
        $branche->delete();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => ' حذف الفرع  - '. $branche->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('branches.index')
            ->with('success', 'تم حذف الفرع بنجاح');
    }
}
