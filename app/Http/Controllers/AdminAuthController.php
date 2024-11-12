<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use PhpParser\Node\Expr\FuncCall;

class AdminAuthController extends Controller
{
    public function showAdminLogin()
    {
        return Inertia::render('Admin/AdminLogin');
    }
    public function showAdminRegister()
    {
        return Inertia::render('Admin/AdminRegister');
    }

    public function AdminRegister(Request $request)
    {
        $adminValidated = $request->validate([
            'admin_name' => 'required|string|max:225',
            'email' => 'required|string|email|max:225|unique:admin', 
            'password' => 'required|string|min:8|confirmed', 
        ]);
        $admin = Admin::create([
            'admin_name' => $adminValidated['admin_name'],
            'email' => $adminValidated['email'],
            'password' => Hash::make($adminValidated['password']), 
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard');
    }

    public function AdminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email', 
            'password' => 'required|string'
        ]);
        if (Auth::guard('admin')->attempt([
            'email'=> $request->email ,
            'password'=>$request->password 
        ])) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withInput()->withErrors([
            'login' => 'Invalid credentials'
        ]);
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}

