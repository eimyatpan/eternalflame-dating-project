<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserUpgradeController extends Controller
{
    public function showUserData()
    {
        $user = Auth::user();
        return Inertia::render('User/UserUpgradeAccount',['user'=>$user]);
    }

    public function storePayment(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();

        // Validate that payment method is provided
        $request->validate([
            'payment_method' => 'required|string|max:255'
        ]);

        // Create a new payment record with a pending status
        $payment = Payment::create([
            'user_id' => $user->user_id,
            'payment_amount' => 225,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'payment_date' => now(),
        ]);

        return redirect()->route('user.upgrade')->with('message', 'Payment successful!');
    }
}
