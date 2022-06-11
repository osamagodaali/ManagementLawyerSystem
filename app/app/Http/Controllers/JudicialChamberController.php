<?php

namespace App\Http\Controllers;

use App\Models\JudicialChamber;
use App\Models\Court;
use App\Models\AdminsActivities;
use App\Models\Admin;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JudicialChamberController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:قائمة الدوائر القضائية', ['only' => ['index']]);
        $this->middleware('permission:انشاء دائرة قضائية', ['only' => ['store']]);
        $this->middleware('permission:تعديل دائرة قضائية', ['only' => ['update']]);
        $this->middleware('permission:حذف دائرة قضائية', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $judicialChamber = JudicialChamber::join('courts','courts.id','=','judicial_chambers.courtid')
                            ->get(['judicial_chambers.*','courts.name as court_name']);
        $courts = Court::all();
        return view('admin.judicial_chambers' , compact('judicialChamber','courts'));
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
        // return $request;
        $validated = $request->validate([
            'name'    => 'required',
            'courtid' => 'required',
            'address' => 'required',
        ],[
            'name.required'     => 'برجاءاضافة اسم الدائرة ',
            'courtid.required'  => 'برجاءاختيار المحكمة',
            'address.required'  => 'برجاءاضافة عنوان المحكمة ',
        ]);
        
        $JudicialChamber = JudicialChamber::create([
            'name'      => $request->name,
            'courtid'   => $request->courtid,
            'address'   => $request->address,
        ]);

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة دائرة جديدة - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JudicialChamber  $judicialChamber
     * @return \Illuminate\Http\Response
     */
    public function show(JudicialChamber $judicialChamber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JudicialChamber  $judicialChamber
     * @return \Illuminate\Http\Response
     */
    public function edit(JudicialChamber $judicialChamber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JudicialChamber  $judicialChamber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required',
            'courtid' => 'required',
            'address' => 'required',
        ],[
            'name.required'     => 'برجاءاضافة اسم الدائرة ',
            'courtid.required'  => 'برجاءاختيار المحكمة',
            'address.required'  => 'برجاءاضافة عنوان المحكمة ',
        ]);
        
        $JudicialChamber = JudicialChamber::find($id);
        $JudicialChamber->name      = $request->name;
        $JudicialChamber->courtid   = $request->courtid;
        $JudicialChamber->address   = $request->address;
        $JudicialChamber->save();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات الدائرة - '. $request->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return back()->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JudicialChamber  $judicialChamber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $JudicialChamber = JudicialChamber::find($request->judicialchambersid);
        $JudicialChamber->delete();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'حذف دائرة قضائية - '. $JudicialChamber->name,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));
        
        return back()->with('success', 'تم الحذف بنجاح');
    }

    public function getJudicialChamber(Request $request)
    {
        $data['JudicialChambers'] = JudicialChamber::where("courtid",$request->courtid)->get(["name","id"]);
        return response()->json($data);
    }
}
