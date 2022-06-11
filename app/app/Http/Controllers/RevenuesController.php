<?php

namespace App\Http\Controllers;

use App\Models\revenues;
use App\Models\Cases;
use App\Models\AdminsActivities;
use App\Models\Admin;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevenuesController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        // $this->middleware('permission:كل الصلاحيات الايرادات', ['only' => ['index','create','store','show','edit','update', 'destroy']]);
        $this->middleware('permission:قائمة الايرادات', ['only' => ['index','show']]);
        $this->middleware('permission:انشاء ايراد', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل ايراد', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف ايراد', ['only' => ['destroy']]);
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $revenues = revenues::join('users', 'users.id', '=', 'revenues.userid')
                ->join('cases', 'cases.id', '=', 'revenues.caseid')
                ->join('admins', 'admins.id', '=', 'revenues.addedby')
                ->get(['revenues.*','users.name', 'cases.case_number' ,'cases.id as caseid' , 'admins.name as employee_name']); 

        $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                ->get(['cases.*','users.id as userid','users.name']);   
        $all_revenues = revenues::sum('value');
        return view('admin.revenues.index' , compact('revenues' , 'cases' , 'all_revenues'));
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
        $validated = $request->validate([
            'caseid'    => 'required',
            'value'     => 'required',
            'details'   => 'required',
        ],[
            'caseid.required' => 'برجاء اختيار القضية  ',
            'value.required'  => 'برجاء ادخال قيمة الايراد ',
            'details.required'  => 'برجاءادخال تفاصيل الايراد  ',
        ]);
        
        $case = Cases::where("id" , $request->caseid)->first();
        $revenue = revenues::create([
            'value'     => $request->value,
            'details'   => $request->details,
            'userid'    => $case->userid,
            'caseid'    => $request->caseid, 
            'addedby'   => Auth::user()->id,
        ]);

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة ايراد جديد للقضية رقم - '. $case->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\revenues  $revenues
     * @return \Illuminate\Http\Response
     */
    public function show(revenues $revenues)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\revenues  $revenues
     * @return \Illuminate\Http\Response
     */
    public function edit(revenues $revenues)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\revenues  $revenues
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'caseid'    => 'required',
            'value'     => 'required',
            'details'   => 'required',

        ],[
            'caseid.required' => 'برجاء اختيار القضية  ',
            'value.required'  => 'برجاء ادخال قيمة الايراد ',
            'details.required'  => 'برجاءادخال تفاصيل الايراد  ',
        ]);
        
        $case = Cases::where("id" , $request->caseid)->first();
        $revenue = revenues::find($id);
        $revenue->value     = $request->value;
        $revenue->details   = $request->details;
        $revenue->userid    = $case->userid;
        $revenue->caseid    = $request->caseid;
        $revenue->addedby   = Auth::user()->id;
        $revenue->save();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات ايراد للقضية رقم - '. $case->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\revenues  $revenues
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $revenue = revenues::find($request->revenueid);
        $case = Cases::find($revenue->caseid);
        $revenue->delete();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'حذف ايراد للقضية رقم - '. $case->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم الحذف بنجاح');
    }
}
