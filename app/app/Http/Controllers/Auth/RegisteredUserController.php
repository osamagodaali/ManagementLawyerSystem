<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Users_wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required', 'string', 'max:255',
            'mobile'    => 'required', 'mobile', 'max:255', 'unique:users',
            'email'     => 'required', 'string', 'email', 'max:255', 'unique:users',
            'password'  => 'required', 'confirmed', Rules\Password::defaults(),
        ],[
            'name.required' => 'برجاء ادخال الاسم ',
            'mobile.unique'   => 'برجاء ادخال رقم الهاتف  غير مكرر',
            'mobile.required'  => 'برجاءادخال رقم الهاتف ',
            'email.unique'   => 'برجاء ادخال بريد الكتروني  غير مكرر',
            'email.required'  => 'برجاءادخال  بريد الكتروني',
            'password.required'  => 'برجاءادخال  كلمة المرور',
        ]);

        $username = Str::random(8);

        $user = User::create([
            'username'      => $username,
            'name'          => $request->name,
            'mobile'        => $request->mobile,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'random_password'=> 0,
            'status'        => 0,
            'password_status'=> 0,
        ]);

        $userid = User::latest()->first()->id;
        $userWallet = Users_wallet::create([
            'userid'        => $userid,
            'wallet_value'  => 0,
            'request_value' => 0,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
