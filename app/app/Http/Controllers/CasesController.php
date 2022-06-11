<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Cases;
use App\Models\CaseDetails;
use App\Models\Case_attachments;
use App\Models\User;
use App\Models\Admin;
use App\Models\Branche;
use App\Models\admin_has_cases_roles;
use App\Models\CasesType;
use App\Models\Court;
use App\Models\JudicialChamber;
use App\Models\revenues;
use App\Models\Expenses;
use App\Models\AdminsActivities;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class CasesController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:قائمة القضايا', ['only' => ['index','casesfilter']]);
        $this->middleware('permission:مشاهدة قضية', ['only' => ['show']]);
        $this->middleware('permission:انشاء قضية', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل قضية', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف قضية', ['only' => ['destroy']]);
        $this->middleware('permission:قائمة القضايا الجديدة', ['only' => ['new_cases','newcasesfilter']]);
        $this->middleware('permission:قائمة القضايا الجارية', ['only' => ['current_cases','currentcasesfilter']]);
        $this->middleware('permission:قائمة القضايا المنتهية', ['only' => ['finished_cases','finishedcasesfilter']]);
        $this->middleware('permission:قائمة القضايا المنتهية', ['only' => ['processes_cases','processescasesfilter']]);
        $this->middleware('permission:قائمة القضايا المنتهية', ['only' => ['collections_cases','collectionscasesfilter']]);
        $this->middleware('permission:اضافة لسير القضية', ['only' => ['add_case_details_form','add_case_details']]);
        $this->middleware('permission:تعديل لسير القضية', ['only' => ['edit_case_details_form','update_case_details']]);
        $this->middleware('permission:حذف لسير القضية', ['only' => ['case_details_destroy']]);
        $this->middleware('permission:تحميل المرفق', ['only' => ['view_case_details_file']]);
        $this->middleware('permission:حذف المرفق', ['only' => ['file_destroy']]);
        $this->middleware('permission:تغيير حالة القضية', ['only' => ['update_case_status']]);
        $this->middleware('permission:التفاصيل المالية للقضية', ['only' => ['case_transactions']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->where('admin_has_cases_roles.admin_id' , $admin_id)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }elseif($cases_availabe == 1){
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::all();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }
        return view('admin.cases.index' , compact('cases' ,'allCases','users','casesType','courts','branches')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        $users = User::all();
        $employees = Admin::all();
        $branches = Branche::all();
        $casesType = CasesType::all();
        $courts = Court::all();
        return view('admin.cases.create', compact('users', 'employees' , 'branches','casesType','courts'));
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
            'userid'        => 'required',
            'brancheid'     => 'required',
            'courtid'       => 'required',
            'judicialchamberid'   => 'required',
            'case_type'     => 'required', 'max:255',
            'case_number'   => 'required',
            'title'         => 'required', 'string','max:255',
            'details'       => 'required', 'string',
            'case_value'    => 'required',
            'payment_status'=> 'required',

        ],[
            'userid.required'       => 'برجاء اختيار العميل  ',
            'brancheid.required'    => 'برجاء اختيار الفرع',
            'courtid.required'      => 'برجاء اختيار المحكمة',
            'judicialchamberid.required'  => 'برجاء اختيار الدائرة القضائية',
            'case_type.required'    => 'برجاء اختيار نوع القضية',
            'case_number.required'  => 'برجاءادخال رقم القضية ',
            'title.required'        => 'برجاءادخال الوصف  ',
            'details.required'      => 'برجاءادخال وصف القضية  ',
            'case_value.required'   => 'برجاءادخال قيمة القضية  ',
            'payment_status.required'  => 'برجاء اختيار حالة الدفع   ',
        ]);
        
        $case = Cases::create([
            'case_number'       => $request->case_number,
            'title'             => $request->title,
            'userid'            => $request->userid,
            'brancheid'         => $request->brancheid, 
            'courtid'           => $request->courtid, 
            'judicialchamberid' => $request->judicialchamberid, 
            'case_type'         => $request->case_type, 
            'details'           => $request->details,
            'value'             => $request->case_value,
            'case_status'       => 1,
            'payment_status'    => $request->payment_status,
        ]);

        $case_id = Cases::latest()->first()->id;

        foreach($request->followby as $follow){
            $perm = admin_has_cases_roles::create([
                'admin_id'  => $follow,
                'case_id'   => $case_id,
            ]);
        }
        if ($request->hasFile('file')) {
            $validatedData = $request->validate([
                'file' => 'mimes:png,jpg,jpeg,pdf',
            ]);
            $file = $request->file('file');
            $fileName = 'case'.$case_id.'-'.time().'-'.$request->file->getClientOriginalName();
            
            $attachments = new Case_attachments();
            $attachments->file_name = $fileName;
            $attachments->caseid = $case_id;
            $attachments->uploadedby = Auth::user()->id;
            $attachments->save();
            // move file
            $request->file->move(public_path('Attachments/case' . $case_id), $fileName);
        }

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة قضية جديدة - '. $request->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));
        
        return redirect()->route('cases.index')->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cases  $cases
     * @return \Illuminate\Http\Response
     */
    public function show($caseid)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$caseid)->count();
            if($check_case_admin > 0){
                $case = Cases::find($caseid);
                $user = User::where('id',$case->userid)->first();
                $employees = admin_has_cases_roles::join("admins", "admins.id","=","admin_has_cases_roles.admin_id")->where('admin_has_cases_roles.case_id' , $caseid)->get("admins.name");
                $branche = Branche::where('id',$case->brancheid)->first();
                $casesType = CasesType::where('id',$case->case_type)->first();
                $courts = Court::where('id',$case->courtid)->first();
                $judicialchamber = JudicialChamber::where('id',$case->judicialchamberid)->first();
                $case_details = CaseDetails::where('caseid',$caseid)->get();
                $case_attachments = Case_attachments::where('caseid',$caseid)->get();
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $case = Cases::find($caseid);
            $user = User::where('id',$case->userid)->first();
            $employees = admin_has_cases_roles::join("admins", "admins.id","=","admin_has_cases_roles.admin_id")->where('admin_has_cases_roles.case_id' , $caseid)->get("admins.name");
            $branche = Branche::where('id',$case->brancheid)->first();
            $casesType = CasesType::where('id',$case->case_type)->first();
            $courts = Court::where('id',$case->courtid)->first();
            $judicialchamber = JudicialChamber::where('id',$case->judicialchamberid)->first();
            $case_details = CaseDetails::where('caseid',$caseid)->get();
            $case_attachments = Case_attachments::where('caseid',$caseid)->get();
        }
        return view('admin.cases.show', compact('case' , 'user', 'employees' , 'branche', 'case_details' , 'case_attachments','casesType','courts','judicialchamber'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cases  $cases
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$id)->count();
            if($check_case_admin > 0){
                $users = User::all();
                $employees = Admin::all();
                $branches = Branche::all();
                $casesType = CasesType::all();
                $courts = Court::all();
                $case = Cases::find($id);
                $caseFollowsBy = DB::table('admin_has_cases_roles')
                    ->where('admin_has_cases_roles.case_id', $id)
                    ->pluck('admin_has_cases_roles.admin_id', 'admin_has_cases_roles.admin_id')
                    ->all();
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $users = User::all();
            $employees = Admin::all();
            $branches = Branche::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $case = Cases::find($id);
            $caseFollowsBy = DB::table('admin_has_cases_roles')
                ->where('admin_has_cases_roles.case_id', $id)
                ->pluck('admin_has_cases_roles.admin_id', 'admin_has_cases_roles.admin_id')
                ->all();
        }
        
        return view('admin.cases.edit', compact('case','users', 'employees' , 'branches' , 'caseFollowsBy', 'casesType' , 'courts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cases  $cases
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$id)->count();
            if($check_case_admin > 0){
                $validated = $request->validate([
                    'userid'        => 'required',
                    'brancheid'     => 'required',
                    'courtid'       => 'required',
                    'judicialchamberid'   => 'required',
                    'case_type'     => 'required', 'max:255',
                    'case_number'   => 'required',
                    'title'         => 'required', 'string','max:255',
                    'details'       => 'required', 'string',
                    'case_value'    => 'required',
                    'payment_status'=> 'required',
        
                ],[
                    'userid.required'       => 'برجاء اختيار العميل  ',
                    'brancheid.required'    => 'برجاء اختيار الفرع',
                    'courtid.required'      => 'برجاء اختيار المحكمة',
                    'judicialchamberid.required'  => 'برجاء اختيار الدائرة القضائية',
                    'case_type.required'    => 'برجاء اختيار نوع القضية',
                    'case_number.required'  => 'برجاءادخال رقم القضية ',
                    'title.required'        => 'برجاءادخال الوصف  ',
                    'details.required'      => 'برجاءادخال وصف القضية  ',
                    'case_value.required'   => 'برجاءادخال قيمة القضية  ',
                    'payment_status.required'  => 'برجاء اختيار حالة الدفع   ',
                ]);
        
                
                $case = Cases::find($id);
                if($request->case_status == 1){
                    $start_case = null;
                    $end_case   = null;
                }elseif($request->case_status == 2){
                    $start_case = Carbon::now();
                    $end_case   = null;
                }elseif($request->case_status == 3){
                    $start_case = $case->start_case;
                    $end_case   = Carbon::now();
                }elseif($request->case_status == 4){
                    $start_case = $case->start_case;
                    $end_case   = null;
                }elseif($request->case_status == 5){
                    $start_case = $case->start_case;
                    $end_case   = null;
                }
        
                $case->case_number      = $request->case_number;
                $case->title            = $request->title;
                $case->userid           = $request->userid;
                $case->brancheid        = $request->brancheid;
                $case->courtid          = $request->courtid;
                $case->judicialchamberid= $request->judicialchamberid;
                $case->case_type        = $request->case_type;
                $case->details          = $request->details;
                $case->value            = $request->case_value;
                $case->case_status      = $request->case_status;
                $case->start_case       = $start_case;
                $case->end_case         = $end_case;
                $case->payment_status   = $request->payment_status;
                $case->save();

                admin_has_cases_roles::where("case_id" , $id)->delete();
                if(!empty($request->followby)){
                    foreach($request->followby as $follow){
                        $perm = admin_has_cases_roles::create([
                            'admin_id'  => $follow,
                            'case_id'   => $id,
                        ]);
                    }
                }

                if ($request->hasFile('file')) {
                    $validatedData = $request->validate([
                        'file' => 'mimes:png,jpg,jpeg,pdf',
                    ]);
                    $file = $request->file('file');
                    $fileName = 'case'.$id.'-'.time().'-'.$request->file->getClientOriginalName();
                    
                    $attachments = new Case_attachments();
                    $attachments->file_name = $fileName;
                    $attachments->caseid = $id;
                    $attachments->uploadedby = Auth::user()->id;
                    $attachments->save();
                    // move file
                    $request->file->move(public_path('Attachments/case' . $id), $fileName);
                }
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $validated = $request->validate([
                'userid'        => 'required',
                'brancheid'     => 'required',
                'courtid'       => 'required',
                'judicialchamberid'   => 'required',
                'case_type'     => 'required', 'max:255',
                'case_number'   => 'required',
                'title'         => 'required', 'string','max:255',
                'details'       => 'required', 'string',
                'case_value'    => 'required',
                'payment_status'=> 'required',
    
            ],[
                'userid.required'       => 'برجاء اختيار العميل  ',
                'brancheid.required'    => 'برجاء اختيار الفرع',
                'courtid.required'      => 'برجاء اختيار المحكمة',
                'judicialchamberid.required'  => 'برجاء اختيار الدائرة القضائية',
                'case_type.required'    => 'برجاء اختيار نوع القضية',
                'case_number.required'  => 'برجاءادخال رقم القضية ',
                'title.required'        => 'برجاءادخال الوصف  ',
                'details.required'      => 'برجاءادخال وصف القضية  ',
                'case_value.required'   => 'برجاءادخال قيمة القضية  ',
                'payment_status.required'  => 'برجاء اختيار حالة الدفع   ',
            ]);
            
            $case = Cases::find($id);
            if($request->case_status == 1){
                $start_case = null;
                $end_case   = null;
            }elseif($request->case_status == 2){
                $start_case = Carbon::now();
                $end_case   = null;
            }elseif($request->case_status == 3){
                $start_case = $case->start_case;
                $end_case   = Carbon::now();
            }elseif($request->case_status == 4){
                $start_case = $case->start_case;
                $end_case   = null;
            }elseif($request->case_status == 5){
                $start_case = $case->start_case;
                $end_case   = null;
            }
    
            $case->case_number      = $request->case_number;
            $case->title            = $request->title;
            $case->userid           = $request->userid;
            $case->brancheid        = $request->brancheid;
            $case->courtid          = $request->courtid;
            $case->judicialchamberid= $request->judicialchamberid;
            $case->case_type        = $request->case_type;
            $case->details          = $request->details;
            $case->value            = $request->case_value;
            $case->case_status      = $request->case_status;
            $case->start_case       = $start_case;
            $case->end_case         = $end_case;
            $case->payment_status   = $request->payment_status;
            $case->save();
    
            admin_has_cases_roles::where("case_id" , $id)->delete();
            if(!empty($request->followby)){
                foreach($request->followby as $follow){
                    $perm = admin_has_cases_roles::create([
                        'admin_id'  => $follow,
                        'case_id'   => $id,
                    ]);
                }
            }
            if ($request->hasFile('file')) {
                $validatedData = $request->validate([
                    'file' => 'mimes:png,jpg,jpeg,pdf',
                ]);
                $file = $request->file('file');
                $fileName = 'case'.$id.'-'.time().'-'.$request->file->getClientOriginalName();
                
                $attachments = new Case_attachments();
                $attachments->file_name = $fileName;
                $attachments->caseid = $id;
                $attachments->uploadedby = Auth::user()->id;
                $attachments->save();
                // move file
                $request->file->move(public_path('Attachments/case' . $id), $fileName);
            }
        }

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات القضية  - '. $request->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('cases.index')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cases  $cases
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$id)->count();
            if($check_case_admin > 0){
                $case_files = Case_attachments::where('caseid',$id)->get();
                if(!empty($case_files)){
                    foreach($case_files as $file){
                        $file  = Case_attachments::find($file->id);
                        $cases = Case_attachments::findOrFail($file->id);
                        $cases->delete();
                        
                        Storage::disk('public_uploads')->delete('case'.$file->caseid.'/'.$file->file_name);
                    }
                }

                $CaseDetails = CaseDetails::where("caseid" , $id)->count();
                if($CaseDetails > 0){
                    CaseDetails::where("caseid" , $id)->delete();
                }
                $admin_has_cases_roles = admin_has_cases_roles::where("case_id" , $id)->count();
                if($admin_has_cases_roles > 0){
                    admin_has_cases_roles::where("case_id" , $id)->delete();
                }
                $case = Cases::find($id);
                $case->delete();
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $case_files = Case_attachments::where('caseid',$id)->get();
            if(!empty($case_files)){
                foreach($case_files as $file){
                    $file  = Case_attachments::find($file->id);
                    $cases = Case_attachments::findOrFail($file->id);
                    $cases->delete();
                    
                    Storage::disk('public_uploads')->delete('case'.$file->caseid.'/'.$file->file_name);
                }
            }

            $CaseDetails = CaseDetails::where("caseid" , $id)->count();
            if($CaseDetails > 0){
                CaseDetails::where("caseid" , $id)->delete();
            }
            $admin_has_cases_roles = admin_has_cases_roles::where("case_id" , $id)->count();
            if($admin_has_cases_roles > 0){
                admin_has_cases_roles::where("case_id" , $id)->delete();
            }
            $case = Cases::find($id);
            $case->delete();
        }

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'حذف القضية رقم  - '. $case->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('cases.index')->with('success', 'تم الحذف بنجاح');
    }

    public function update_case_status(Request $request)
    {
        $caseid = $request->caseid;
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$caseid)->count();
            if($check_case_admin > 0){
                if($request->case_status == 1){
                    $case = Cases::find($caseid);
                    $case->case_status      = $request->case_status;
                    $case->start_case       = null;
                    $case->end_case         = null;
                    $case->save();
                }elseif($request->case_status == 2){
                    $case = Cases::find($caseid);
                    $case->case_status      = $request->case_status;
                    $case->start_case       = Carbon::now();
                    $case->end_case         = null;
                    $case->save();
                }elseif($request->case_status == 3){
                    $case = Cases::find($caseid);
                    $case->case_status      = $request->case_status;
                    $case->end_case         = Carbon::now();
                    $case->save();
                }elseif($request->case_status == 4){
                    $case = Cases::find($caseid);
                    $case->case_status      = $request->case_status;
                    $case->end_case         = null;
                    $case->save();
                }elseif($request->case_status == 5){
                    $case = Cases::find($caseid);
                    $case->case_status      = $request->case_status;
                    $case->end_case         = null;
                    $case->save();
                }

            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            if($request->case_status == 1){
                $case = Cases::find($caseid);
                $case->case_status      = $request->case_status;
                $case->start_case       = '';
                $case->end_case         = '';
                $case->save();
            }elseif($request->case_status == 2){
                $case = Cases::find($caseid);
                $case->case_status      = $request->case_status;
                $case->start_case       = date("Y-m-d");
                $case->end_case         = '';
                $case->save();
            }elseif($request->case_status == 3){
                $case = Cases::find($caseid);
                $case->case_status      = $request->case_status;
                $case->end_case         = date("Y-m-d");
                $case->save();
            }elseif($request->case_status == 4){
                $case = Cases::find($caseid);
                $case->case_status      = $request->case_status;
                $case->end_case         = null;
                $case->save();
            }elseif($request->case_status == 5){
                $case = Cases::find($caseid);
                $case->case_status      = $request->case_status;
                $case->end_case         = null;
                $case->save();
            }
        }

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تحديث حالة القضية رقم  - '. $case->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('cases.index')->with('success', 'تم تعديل حالة القضية بنجاح');
    }

    public function case_transactions($caseid)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$caseid)->count();
            if($check_case_admin > 0){
                $revenues = revenues::where('caseid',$caseid)->get();
                $expenses = Expenses::where('caseid',$caseid)->get();
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $revenues = revenues::where('caseid',$caseid)->get();
            $expenses = Expenses::where('caseid',$caseid)->get();
        }
        
        return view('admin.cases.case_transactions', compact('revenues','expenses'));
    }

    public function new_cases()
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('cases.case_status' , 1)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('case_status',1)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }elseif($cases_availabe == 1){
                $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('cases.case_status' , 1)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',1)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }
        
        return view('admin.cases.new_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
    }

    public function current_cases()
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('cases.case_status' , 2)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('case_status',2)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }elseif($cases_availabe == 1){
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('cases.case_status' , 2)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',2)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }
        
        return view('admin.cases.current_cases' , compact('cases' ,'allCases','users','casesType','courts','branches')); 
    }

    public function finished_cases()
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('cases.case_status' , 3)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('case_status',3)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }elseif($cases_availabe == 1){
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('cases.case_status' , 3)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',3)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }
        
        return view('admin.cases.finished_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
    }

    public function processes_cases()
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('cases.case_status' , 4)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('case_status',4)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }elseif($cases_availabe == 1){
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('cases.case_status' , 4)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',4)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }
        
        return view('admin.cases.processes_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
    }

    public function collections_cases()
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('cases.case_status' , 5)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->where('admin_has_cases_roles.admin_id' , $admin_id)
                ->where('case_status',5)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }elseif($cases_availabe == 1){
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('cases.case_status' , 5)
                ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',5)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
        }
        
        return view('admin.cases.collections_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
    }

    public function add_case_details_form($case_id){
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$case_id)->count();
            if($check_case_admin > 0){
                return view('admin.cases.add_details', compact('case_id'));
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            return view('admin.cases.add_details', compact('case_id'));
        }
        
    }

    public function add_case_details(Request $request){
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        $case_data = Cases::find($request->caseid);
        if($cases_availabe == 0){
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$request->caseid)->count();
            if($check_case_admin > 0){
                $validated = $request->validate([
                    'case_decisions'    => 'required',
                    'suggested_action'  => 'required',
                    'nextfollowdate'    => 'required',
                ],[
                    'case_decisions.required'   => 'برجاء اضافة القرارات',
                    'suggested_action.required' => 'برجاء اضافة الاجراء المقترح',
                    'nextfollowdate.required'   => 'برجاء اضافة موعد الجلسة القادم',
                ]);
                
                $caseDetails = CaseDetails::create([
                    'caseid'            => $request->caseid,
                    'case_decisions'    => $request->case_decisions,
                    'suggested_action'  => $request->suggested_action,
                    'details'           => $request->details,
                    'nextfollowdate'    => $request->nextfollowdate,
                    'followby'          => Auth::user()->id,
                ]);
        
                if ($request->hasFile('file')) {
                    $validatedData = $request->validate([
                        'file' => 'mimes:png,jpg,jpeg,pdf',
                    ]);
                    $case_details = CaseDetails::latest()->first()->id;
                    $file = $request->file('file');
                    $caseid = $request->caseid;
                    $fileName = 'case'.$caseid.'-details'.$case_details.'-'.time().'-'.$request->file->getClientOriginalName();
                    
                    $attachments = new Case_attachments();
                    $attachments->file_name = $fileName;
                    $attachments->caseid = $caseid;
                    $attachments->casedetailsid = $case_details;
                    $attachments->uploadedby = Auth::user()->id;
                    $attachments->save();
                    // move file
                    $request->file->move(public_path('Attachments/case' . $caseid), $fileName);
                }

                // add activity
                $activity = [
                    'admin_id'      => Auth::user()->id,
                    'description'   => 'إضافة تفاصيل القضية رقم - '.$case_data->case_number,
                ];
                $adminActivity = AdminsActivities::create($activity);
                $main_admins = Admin::role('المدير  العام')->get();

                Notification::send($main_admins, new ActivitiesNotifications($activity));
                
                return redirect()->route('cases.index')->with('success', 'تم الاضافة بنجاح');
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $validated = $request->validate([
                'case_decisions'    => 'required',
                'suggested_action'  => 'required',
                'nextfollowdate'    => 'required',
            ],[
                'case_decisions.required'   => 'برجاء اضافة القرارات',
                'suggested_action.required' => 'برجاء اضافة الاجراء المقترح',
                'nextfollowdate.required'   => 'برجاء اضافة موعد الجلسة القادم',
            ]);

            $caseDetails = CaseDetails::create([
                'caseid'            => $request->caseid,
                'case_decisions'    => $request->case_decisions,
                'suggested_action'  => $request->suggested_action,
                'details'           => $request->details,
                'nextfollowdate'    => $request->nextfollowdate,
                'followby'          => Auth::user()->id,
            ]);
    
            if ($request->hasFile('file')) {
                $validatedData = $request->validate([
                    'file' => 'mimes:png,jpg,jpeg,pdf',
                ]);
                $case_details = CaseDetails::latest()->first()->id;
                $file = $request->file('file');
                $caseid = $request->caseid;
                $fileName = 'case'.$caseid.'-details'.$case_details.'-'.time().'-'.$request->file->getClientOriginalName();
                
                $attachments = new Case_attachments();
                $attachments->file_name = $fileName;
                $attachments->caseid = $caseid;
                $attachments->casedetailsid = $case_details;
                $attachments->uploadedby = Auth::user()->id;
                $attachments->save();
                // move file
                $request->file->move(public_path('Attachments/case' . $caseid), $fileName);
            }

            // add activity
            $activity = [
                'admin_id'      => Auth::user()->id,
                'description'   => 'إضافة تفاصيل القضية رقم - '.$case_data->case_number,
            ];
            $adminActivity = AdminsActivities::create($activity);
            $main_admins = Admin::role('المدير  العام')->get();

            Notification::send($main_admins, new ActivitiesNotifications($activity));
            
            return redirect()->route('cases.index')->with('success', 'تم الاضافة بنجاح');
        }
    }

    public function edit_case_details_form($case_id){
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $case = CaseDetails::find($case_id);
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$case->caseid)->count();
            if($check_case_admin > 0){
                return view('admin.cases.edit_details', compact('case'));
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $case = CaseDetails::find($case_id);
            return view('admin.cases.edit_details', compact('case'));
        }
    }

    public function update_case_details(Request $request){
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        $case_data = Cases::find($request->caseid);
        if($cases_availabe == 0){
            $case_details = CaseDetails::where('id',$request->caseid)->first();
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$case_details->caseid)->count();
            if($check_case_admin > 0){
                $validated = $request->validate([
                    'case_decisions'    => 'required',
                    'suggested_action'  => 'required',
                    'nextfollowdate'    => 'required',
                ],[
                    'case_decisions.required'   => 'برجاء اضافة القرارات',
                    'suggested_action.required' => 'برجاء اضافة الاجراء المقترح',
                    'nextfollowdate.required'   => 'برجاء اضافة موعد الجلسة القادم',
                ]);
    
                $caseDetails = CaseDetails::find($request->caseid);
                $caseDetails->case_decisions    = $request->case_decisions;
                $caseDetails->suggested_action  = $request->suggested_action;
                $caseDetails->details           = $request->details;
                $caseDetails->followby          = Auth::user()->id;
                $caseDetails->nextfollowdate    = $request->nextfollowdate;
                $caseDetails->save();
        
                if ($request->hasFile('file')) {
                    $validatedData = $request->validate([
                        'file' => 'mimes:png,jpg,jpeg,pdf',
                    ]);
                    $file = $request->file('file');
                    $fileName = 'case'.$case_details->caseid.'-details'.$case_details->id.'-'.time().'-'.$request->file->getClientOriginalName();
                    
                    $attachments = new Case_attachments();
                    $attachments->file_name = $fileName;
                    $attachments->caseid = $case_details->caseid;
                    $attachments->casedetailsid = $case_details->id;
                    $attachments->uploadedby = Auth::user()->id;
                    $attachments->save();
                    // move file
                    $request->file->move(public_path('Attachments/case' . $case_details->caseid), $fileName);
                }

                // add activity
                $activity = [
                    'admin_id'      => Auth::user()->id,
                    'description'   => 'تعديل سير القضية - '.$case_data->case_number,
                ];
                $adminActivity = AdminsActivities::create($activity);
                $main_admins = Admin::role('المدير  العام')->get();

                Notification::send($main_admins, new ActivitiesNotifications($activity));
                
                return redirect()->route('cases.index')->with('success', 'تم التعديل بنجاح');
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $validated = $request->validate([
                'case_decisions'    => 'required',
                'suggested_action'  => 'required',
                'nextfollowdate'    => 'required',
            ],[
                'case_decisions.required'   => 'برجاء اضافة القرارات',
                'suggested_action.required' => 'برجاء اضافة الاجراء المقترح',
                'nextfollowdate.required'   => 'برجاء اضافة موعد الجلسة القادم',
            ]);

            $caseDetails = CaseDetails::find($request->caseid);
            $caseDetails->case_decisions    = $request->case_decisions;
            $caseDetails->suggested_action  = $request->suggested_action;
            $caseDetails->details           = $request->details;
            $caseDetails->followby          = Auth::user()->id;
            $caseDetails->nextfollowdate    = $request->nextfollowdate;
            $caseDetails->save();
    
            if ($request->hasFile('file')) {
                $validatedData = $request->validate([
                    'file' => 'mimes:png,jpg,jpeg,pdf',
                ]);
                $case_details = CaseDetails::where('id',$request->caseid)->first();
                
                $file = $request->file('file');
                $fileName = 'case'.$case_details->caseid.'-details'.$case_details->id.'-'.time().'-'.$request->file->getClientOriginalName();
                
                $attachments = new Case_attachments();
                $attachments->file_name = $fileName;
                $attachments->caseid = $case_details->caseid;
                $attachments->casedetailsid = $case_details->id;
                $attachments->uploadedby = Auth::user()->id;
                $attachments->save();
                // move file
                $request->file->move(public_path('Attachments/case' . $case_details->caseid), $fileName);
            }

            // add activity
            $activity = [
                'admin_id'      => Auth::user()->id,
                'description'   => 'تعديل سير القضية - '.$case_data->case_number,
            ];
            $adminActivity = AdminsActivities::create($activity);
            $main_admins = Admin::role('المدير  العام')->get();

            Notification::send($main_admins, new ActivitiesNotifications($activity));

            return redirect()->route('cases.index')->with('success', 'تم التعديل بنجاح');
        }
        
    }

    public function case_details_destroy(Request $request){
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        
        if($cases_availabe == 0){
            $case_details = CaseDetails::findOrFail($request->casedetailsid);
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$case_details->caseid)->count();
            if($check_case_admin > 0){
                if(!empty($case_details_files)){
                    foreach($case_details_files as $file){
                        $file  = Case_attachments::find($file->id);
                        $case_data = Cases::find($file->caseid);
                        $cases = Case_attachments::findOrFail($file->id);
                        $cases->delete();
                        Storage::disk('public_uploads')->delete('case'.$file->caseid.'/'.$file->file_name);
                    }
                }
                $case_details = CaseDetails::findOrFail($request->casedetailsid);
                $case_details->delete();

                
                // add activity
                $activity = [
                    'admin_id'      => Auth::user()->id,
                    'description'   => 'حذف تفاصيل القضية - '.$case_data->case_number,
                ];
                $adminActivity = AdminsActivities::create($activity);
                $main_admins = Admin::role('المدير  العام')->get();

                Notification::send($main_admins, new ActivitiesNotifications($activity));

                session()->flash('success', 'تم حذف التفاصيل والمرفقات بنجاح');
                return back();
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $case_details_files = Case_attachments::where('casedetailsid',$request->casedetailsid)->get();
            if(!empty($case_details_files)){
                foreach($case_details_files as $file){
                    $file  = Case_attachments::find($file->id);
                    $case_data = Cases::find($file->caseid);
                    $cases = Case_attachments::findOrFail($file->id);
                    $cases->delete();
                    
                    Storage::disk('public_uploads')->delete('case'.$file->caseid.'/'.$file->file_name);
                }
            }
            $case_details = CaseDetails::findOrFail($request->casedetailsid);
            $case_details->delete();

            // add activity
            $activity = [
                'admin_id'      => Auth::user()->id,
                'description'   => 'حذف تفاصيل القضية - '.$case_data->case_number,
            ];
            $adminActivity = AdminsActivities::create($activity);
            $main_admins = Admin::role('المدير  العام')->get();

            Notification::send($main_admins, new ActivitiesNotifications($activity));
            
            session()->flash('success', 'تم حذف التفاصيل والمرفقات بنجاح');
            return back();
        }
        
    }

    public function view_case_details_file($fileid){
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $file = Case_attachments::find($fileid);
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$file->caseid)->count();
            if($check_case_admin > 0){
                $files = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix('case'.$file->caseid.'/'.$file->file_name);
                return response()->file($files);
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $file = Case_attachments::find($fileid);
            $files = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix('case'.$file->caseid.'/'.$file->file_name);
            return response()->file($files);
        }
    }

    public function file_destroy(Request $request)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $file  = Case_attachments::find($request->fileid);
            $case_data = Cases::find($file->caseid);
            $check_case_admin =admin_has_cases_roles::where('admin_id' ,'=',$admin_id)->where('case_id' ,'=',$file->caseid)->count();
            if($check_case_admin > 0){
                $cases = Case_attachments::findOrFail($request->fileid);
                $cases->delete();
                
                Storage::disk('public_uploads')->delete('case'.$file->caseid.'/'.$file->file_name);

                // add activity
                $activity = [
                    'admin_id'      => Auth::user()->id,
                    'description'   => 'حذف مرفقات القضية - '.$case_data->case_number,
                ];
                $adminActivity = AdminsActivities::create($activity);
                $main_admins = Admin::role('المدير  العام')->get();

                Notification::send($main_admins, new ActivitiesNotifications($activity));

                session()->flash('success', 'تم حذف المرفق بنجاح');
                return back();
            }else{
                return redirect()->route('cases.index')->with('error', 'ليس لديك الصلاحية لهذه الصفحة');
            }
        }elseif($cases_availabe == 1){
            $file  = Case_attachments::find($request->fileid);
            $case_data = Cases::find($file->caseid);
            $cases = Case_attachments::findOrFail($request->fileid);
            $cases->delete();
            
            Storage::disk('public_uploads')->delete('case'.$file->caseid.'/'.$file->file_name);

            // add activity
            $activity = [
                'admin_id'      => Auth::user()->id,
                'description'   => 'حذف مرفقات القضية - '.$case_data->case_number,
            ];
            $adminActivity = AdminsActivities::create($activity);
            $main_admins = Admin::role('المدير  العام')->get();

            Notification::send($main_admins, new ActivitiesNotifications($activity));

            session()->flash('success', 'تم حذف المرفق بنجاح');
            return back();
        }
    }

    public function casesfilter(Request $request)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
            
            return view('admin.cases.index' , compact('cases' ,'allCases','users','casesType','courts','branches'));
        }elseif($cases_availabe == 1){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;

            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::all();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
            
            return view('admin.cases.index' , compact('cases' ,'allCases','users','casesType','courts','branches')); 
        }
         
    }

    public function newcasesfilter(Request $request)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status','=', 1)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status',1)->get();
            $users = User::all();
            
            return view('admin.cases.new_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
        }elseif($cases_availabe == 1){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('case_status','=', 1)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',1)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
            
            return view('admin.cases.new_cases' , compact('cases' ,'allCases','users','casesType','courts','branches')); 
        }
         
    }

    public function currentcasesfilter(Request $request)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status','=', 2)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status',2)->get();
            $users = User::all();
            
            return view('admin.cases.current_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
        }elseif($cases_availabe == 1){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('case_status','=', 2)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',2)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
            
            return view('admin.cases.current_cases' , compact('cases' ,'allCases','users','casesType','courts','branches')); 
        }
         
    }

    public function finishedcasesfilter(Request $request)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status','=', 3)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status',3)->get();
            $users = User::all();
            
            return view('admin.cases.finished_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
        }elseif($cases_availabe == 1){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('case_status','=', 3)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',3)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
            
            return view('admin.cases.finished_cases' , compact('cases' ,'allCases','users','casesType','courts','branches')); 
        }
         
    }

    public function processescasesfilter(Request $request)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status','=', 4)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status',4)->get();
            $users = User::all();
            
            return view('admin.cases.finished_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
        }elseif($cases_availabe == 1){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('case_status','=', 4)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',4)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
            
            return view('admin.cases.finished_cases' , compact('cases' ,'allCases','users','casesType','courts','branches')); 
        }
         
    }

    public function collectionscasesfilter(Request $request)
    {
        $admin_id = Auth::user()->id;
        $cases_availabe = Admin::where('id',$admin_id)->value('cases_availabe'); 
        if($cases_availabe == 0){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status','=', 5)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                    ->where('admin_has_cases_roles.admin_id' , $admin_id)
                    ->where('case_status',5)->get();
            $users = User::all();
            
            return view('admin.cases.finished_cases' , compact('cases' ,'allCases','users','casesType','courts','branches'));
        }elseif($cases_availabe == 1){
            $username           = $request->username ;
            $usernationalid     = $request->usernationalid ;
            $usermobile         = $request->usermobile ;
            $case_number        = $request->case_number;
            $case_type          = $request->case_type ;
            $courtid            = $request->courtid ;
            $judicialchamberid  = $request->judicialchamberid ;
            $case_status        = $request->case_status ;
            $payment_status     = $request->payment_status ;
            $branche            = $request->branche ;
            $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                    ->join('branches', 'branches.id', '=', 'cases.brancheid')
                    ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                    ->join('courts', 'courts.id', '=', 'cases.courtid')
                    ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                    ->where('case_status','=', 5)
                    ->where(function($query) use ($username){
                        if(!empty($username)){
                            $query->where('cases.userid','=', $username);
                        }
                        
                    })
                    ->where(function($query) use ($usernationalid){
                        if(!empty($usernationalid)){
                            $query->where('cases.userid','=', $usernationalid);
                        }
                        
                    })
                    ->where(function($query) use ($usermobile){
                        if(!empty($usermobile)){
                            $query->where('cases.userid','=', $usermobile);
                        }
                        
                    })
                    ->where(function($query) use ($case_number){
                        if(!empty($case_number)){
                            $query->where('cases.case_number','=', $case_number);
                        }
                        
                    })
                    ->where(function($query) use ($case_type){
                        if(!empty($case_type)){
                            $query->where('cases.case_type','=', $case_type);
                        }
                        
                    })
                    ->where(function($query) use ($courtid){
                        if(!empty($courtid)){
                            $query->where('cases.courtid','=', $courtid);
                        }
                        
                    })
                    ->where(function($query) use ($judicialchamberid){
                        if(!empty($judicialchamberid)){
                            $query->where('cases.judicialchamberid','=', $judicialchamberid);
                        }
                        
                    })
                    ->where(function($query) use ($case_status){
                        if(!empty($case_status)){
                            $query->where('cases.case_status','=', $case_status);
                        }
                        
                    })
                    ->where(function($query) use ($payment_status){
                        if(!empty($payment_status)){
                            $query->where('cases.payment_status','=', $payment_status);
                        }
                        
                    })
                    ->where(function($query) use ($branche){
                        if(!empty($branche)){
                            $query->where('cases.brancheid','=', $branche);
                        }
                        
                    })
                    ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
            $allCases = Cases::where('case_status',5)->get();
            $users = User::all();
            $casesType = CasesType::all();
            $courts = Court::all();
            $branches = Branche::all();
            
            return view('admin.cases.finished_cases' , compact('cases' ,'allCases','users','casesType','courts','branches')); 
        }
         
    }

    public function emplyee_cases($employeeid){
        $cases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
                ->join('users', 'users.id', '=', 'cases.userid')
                ->join('branches', 'branches.id', '=', 'cases.brancheid')
                ->join('cases_types', 'cases_types.id', '=', 'cases.case_type')
                ->join('courts', 'courts.id', '=', 'cases.courtid')
                ->join('judicial_chambers', 'judicial_chambers.id', '=', 'cases.judicialchamberid')
                ->where('admin_has_cases_roles.admin_id' , $employeeid)
            ->get(['cases.*','users.name', 'branches.name as branchename','cases_types.name as cases_type_name','courts.name as court_name','judicial_chambers.name as judicial_chamber_name']);
        $allCases = Cases::join("admin_has_cases_roles", "admin_has_cases_roles.case_id","=","cases.id")
            ->where('admin_has_cases_roles.admin_id' , $employeeid)->get();

        return view('admin.cases.emplyee_cases' , compact('cases' ,'allCases'));
    }

}
