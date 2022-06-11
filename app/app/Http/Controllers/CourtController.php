<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\AdminsActivities;
use App\Models\Admin;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:قائمة المحاكم', ['only' => ['index']]);
        $this->middleware('permission:انشاء محكمة جديدة', ['only' => ['store']]);
        $this->middleware('permission:تعديل محكمة', ['only' => ['update']]);
        $this->middleware('permission:حذف محكمة', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $courts = Court::all();
        return view('admin.courts' , compact('courts'));
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
            'address' => 'required',
        ],[
            'name.required'     => 'برجاءاضافة اسم المحكمة ',
            'address.required'  => 'برجاءاضافة عنوان المحكمة ',
        ]);
        
        $Court = Court::create([
            'name'      => $request->name,
            'address'   => $request->address,
        ]);

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة محكمة جديدة - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Court  $court
     * @return \Illuminate\Http\Response
     */
    public function show(Court $court)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Court  $court
     * @return \Illuminate\Http\Response
     */
    public function edit(Court $court)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Court  $court
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required',
            'address' => 'required',
        ],[
            'name.required'     => 'برجاءاضافة اسم المحكمة ',
            'address.required'  => 'برجاءاضافة عنوان المحكمة ',
        ]);
        
        $Court = Court::find($id);
        $Court->name    = $request->name;
        $Court->address = $request->address;
        $Court->save();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات المحكمة - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Court  $court
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // return $request->courtid;
        $Court = Court::find($request->courtid);
        $Court->delete();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'حذف بيانات المحكمة - '. $Court->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم الحذف بنجاح');
    }
}
