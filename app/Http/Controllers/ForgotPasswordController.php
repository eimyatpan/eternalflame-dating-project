<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return back()->withErrors(['email' => 'No user found with this email.']);
        }
    
        $user->update(['password' => Hash::make($request->password)]);
    
        // Use Inertia::location to navigate to the home page
        return back()->with('success', 'Password updated successfully');
    }
}
