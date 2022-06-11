<?php

namespace App\Http\Controllers;

use App\Models\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;


class AdminAuthController extends Controller
{
    public function index()
    {
        return redirect('admin');
    }

    public function login()
    {
        if (Auth::guard('webadmin')->check()) {
            return redirect('admin/dashboard');
        }
        return view('admin.login');
    }

    public function handleLogin(Request $req)
    {
        if(Auth::guard('webadmin')->attempt($req->only(['email', 'password']))){
            return redirect('admin/dashboard');
        }else{
            return redirect()->back()->with('error', 'Invalid Credentials');
        }        
    }

    public function destroy(Request $request)
    {
        Auth::guard('webadmin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('admin/login');
    }
}
