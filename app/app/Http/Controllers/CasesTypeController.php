<?php

namespace App\Http\Controllers;

use App\Models\CasesType;
use App\Models\AdminsActivities;
use App\Models\Admin;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CasesTypeController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:قائمة انواع القضايا', ['only' => ['index']]);
        $this->middleware('permission:انشاء نوع قضية', ['only' => ['store']]);
        $this->middleware('permission:تعديل نوع القضية', ['only' => ['update']]);
        $this->middleware('permission:حذف نوع القضية', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $casesTypes = CasesType::all();   
        return view('admin.cases_types' , compact('casesTypes'));
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
            'name'    => 'required',
        ],[
            'name.required' => 'برجاءاضافة نوع القضية',
        ]);
        
        $CasesType = CasesType::create([
            'name'      => $request->name,
        ]);

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة نوع قضية جديد - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CasesType  $casesType
     * @return \Illuminate\Http\Response
     */
    public function show(CasesType $casesType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CasesType  $casesType
     * @return \Illuminate\Http\Response
     */
    public function edit(CasesType $casesType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CasesType  $casesType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required',
        ],[
            'name.required' => 'برجاءاضافة نوع القضية',
        ]);
        
        $CasesType = CasesType::find($id);
        $CasesType->name  = $request->name;
        $CasesType->save();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات نوع القضية   - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CasesType  $casesType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $CasesType = CasesType::find($request->casestypeid);
        $CasesType->delete();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'حذف بيانات نوع القضية - '. $CasesType->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));
        
        return back()->with('success', 'تم الحذف بنجاح');
    }
}
