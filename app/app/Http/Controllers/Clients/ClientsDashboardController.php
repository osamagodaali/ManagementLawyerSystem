<?php

namespace App\Http\Controllers\Clients;

use App\Models\User;
use App\Models\Users_wallet;
use App\Models\Users_wallet_transaction;
use App\Models\Cases;
use App\Models\CaseDetails;
use App\Models\revenues;
use App\Models\expenses;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;



class ClientsDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $user_data = User::where('id' , Auth::user()->id)->first();
        if($user_data->password_status == 0){
            return view('clients.dashboard');
        }elseif($user_data->password_status == 1){
            return view('clients.change_password' , compact('user_data'));
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
    public function edit($id)
    {
        //
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
        //
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

    public function account(){
        $user_id = Auth::user()->id;
        $wallet = Users_wallet::where('userid' , $user_id)->first();
        $wallet_value = $wallet->wallet_value ;
        $wallet_transactions = Users_wallet_transaction::where('userid' , $user_id)->get();
        $total_user_revenue = Users_wallet_transaction::where('userid' , $user_id)->where('type' , 1)->sum('value');
        $total_user_expenses = Users_wallet_transaction::where('userid' , $user_id)->where('type' , 2)->sum('value');
        return view('clients.account' , compact('wallet_transactions','wallet_value','total_user_revenue' ,'total_user_expenses'));
    }

    public function profile(){
        $user_id = Auth::user()->id;
        $user = User::where('id' , $user_id)->first();
        return view('clients.profile' , compact('user'));
    }

    public function update_profile(Request $request){

        $validated = $request->validate([
            'name'      => 'required',
            'mobile'    => 'required',
            'email'     => 'required',
            'nationalid'=> 'required',
            'address'   => 'required',
        ],[
            'name.required'     => 'برجاء ادخال الاسم بالكامل',
            'mobile.required'   => 'برجاء ادخال رقم الهاتف ',
            'email.required'    => 'برجاء ادخال البريد الالكتروني ',
            'nationalid .required'=> 'برجاء ادخال الرقم  القومي  ',
            'address.required'  => 'برجاء ادخال العنوان  ',
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
            $user_password = Hash::make($request->password) ;
        }else{
            $user_password = Auth::user()->password;
        }

        $user_id    = Auth::user()->id;
        $user_data  = User::findOrFail($user_id);
        $user_data->update([
            'name'      => $request->name ,
            'email'     => $request->email ,
            'mobile'    => $request->mobile ,
            'nationalid'=> $request->nationalid ,
            'password'  => $user_password ,
            'address'   => $request->address ,
        ]);

        session()->flash('success','تم التحديث بنجاح');
        return back(); 
    }

    public function change_password(Request $request){
        $this->validate($request, [
            'email'     => 'required', 'email',
            'password'  => 'required', 'confirmed', Rules\Password::defaults(), 
        ]);
        
        $user_id    = Auth::user()->id;
        $user_data  = User::findOrFail($user_id);
        $user_data->update([
            'password'          => Hash::make($request->password),
            'remember_token'    => Str::random(60), 
            'password_status'   => 0 ,
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function cases(){
        $cases = Cases::where('userid' , Auth::user()->id)->get();
        return view('clients.cases' , compact('cases'));
    }

    public function cases_details($id){
        $case = Cases::find($id);
        if($case  && $case->userid == Auth::user()->id ){
            $casedetails = CaseDetails::join('cases', 'cases.id', '=', 'case_details.caseid')
                ->where('case_details.caseid' , '=' ,$id)
                ->where('cases.userid' , '=' , Auth::user()->id)
                ->get(['case_details.*','cases.case_number', 'cases.title as case_title']);
            return view('clients.cases_details' , compact('case','casedetails'));
        }else{
            return redirect()->route('dashboard.index')->with('success', 'غير مسموح لك بالدخول لهذه الصفحة');
        }
    }

    public function cases_payments(){
        $revenues = Revenues::join('cases', 'cases.id', '=', 'revenues.caseid')
                ->where('revenues.userid' , Auth::user()->id)
                ->get(['revenues.*','cases.case_number', 'cases.title as case_title']);
        return view('clients.cases_payments', compact('revenues'));
    }

}
