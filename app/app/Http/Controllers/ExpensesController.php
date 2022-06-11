<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Cases;
use App\Models\AdminsActivities;
use App\Models\Admin;
use App\Notifications\ActivitiesNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        // $this->middleware('permission:كل الصلاحيات المصروفات', ['only' => ['index','create','store','show','edit','update', 'destroy']]);
        $this->middleware('permission:قائمة المصروفات', ['only' => ['index','show']]);
        $this->middleware('permission:انشاء مصروف', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل مصروف', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف مصروف', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expenses::join('users', 'users.id', '=', 'expenses.userid')
                ->join('cases', 'cases.id', '=', 'expenses.caseid')
                ->join('admins', 'admins.id', '=', 'expenses.addedby')
                ->get(['expenses.*','users.name', 'cases.case_number' ,'cases.id as caseid' , 'admins.name as employee_name']); 
        $cases = Cases::join('users', 'users.id', '=', 'cases.userid')
                ->get(['cases.*','users.id as userid','users.name']);     
        $all_expenses = Expenses::sum('value');
        return view('admin.expenses.index' , compact('expenses','cases' , 'all_expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cases = Cases::all();
        return view('admin.expenses.create', compact('cases'));
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
        $expense = Expenses::create([
            'value'     => $request->value,
            'details'   => $request->details,
            'userid'    => $case->userid,
            'caseid'    => $request->caseid, 
            'addedby'   => Auth::user()->id,
        ]);

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'إضافة مصروف للقضية رقم - '. $case->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('expenses.index')->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function show(expenses $expenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function edit(expenses $expenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\expenses  $expenses
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
        $expense = Expenses::find($id);
        $expense->value     = $request->value;
        $expense->details   = $request->details;
        $expense->userid    = $case->userid;
        $expense->caseid    = $request->caseid;
        $expense->addedby   = Auth::user()->id;
        $expense->save();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'تعديل بيانات مصروف للقضية رقم  - '. $case->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('expenses.index')->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\expenses  $expenses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $expense = Expenses::find($request->expenseid);
        $case = Cases::find($expense->caseid);
        $expense->delete();

        // add activity
        $activity = [
            'admin_id'      => Auth::user()->id,
            'description'   => 'حذف مصروف للقضية رقم - '. $case->case_number,
        ];
        $adminActivity = AdminsActivities::create($activity);
        $main_admins = Admin::role('المدير  العام')->get();

        Notification::send($main_admins, new ActivitiesNotifications($activity));

        return redirect()->route('expenses.index')->with('success', 'تم الحذف بنجاح');
    }
}
