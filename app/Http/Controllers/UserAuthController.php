<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function UserRegister(Request $request)
    {
        $userValidated = $request->validate([
            'user_name' => 'required|string|max:225',
            'email' => 'required|string|email|max:225|unique:users',
            'user_gender' => 'required|string',
            'user_dateOfBirth' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $age = $this->calculateAge($userValidated['user_dateOfBirth']);

        if ($age < 18) {
            return redirect()->back()->withErrors(['user_dateOfBirth' => 'You  must be at least 18 years old to register']);
        }


        $user = User::create([
            'user_name' => $userValidated['user_name'],
            'email' => $userValidated['email'],
            'user_gender' => $userValidated['user_gender'],
            'user_dateOfBirth' => $userValidated['user_dateOfBirth'],
            'password' => Hash::make($userValidated['password']),
            'user_age' => $age,
        ]);

        Auth::login($user);
        return redirect()->route('user.dashboard');
    }

    private function calculateAge($birthday)
    {
        $birthDate = new \DateTime($birthday);
        $today = new \DateTime('today');
        $age = $today->diff($birthDate)->y;
        return $age;
    }

    public function UserLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt([
            'email'=>$request->email,
            'password'=>$request->password
        ])){
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['login'=>'Invalid credentials']);
    }
    public function UserLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
}
