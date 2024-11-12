<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Payment;
use App\Models\PremiumUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function showPaymentLists()
    {
        // Include user_id in the query
        $payments = DB::table('payment')
            ->join('users', 'payment.user_id', '=', 'users.user_id')
            ->select(
                'payment.payment_id',
                'users.user_id',  // Include user_id here
                'users.user_name',
                'payment.payment_amount',
                'payment.payment_date',
                'payment.payment_method',
                'payment.payment_status'
            )
            ->orderBy('payment.created_at', 'desc')
            ->get();

        // Pass the payment data to the Vue component
        return Inertia::render('Admin/AdminPaymentList', [
            'payment' => $payments // Renamed key for consistency
        ]);
    }

    public function updatePaymentStatus(Request $request, $payment_id, $action)
    {
        $payment = Payment::find($payment_id);
    
        if ($payment && $action === 'approve' && $payment->payment_status !== 'completed') {
            // Update payment status to 'completed'
            $payment->payment_status = 'completed';
            $payment->save();
    
            // Create new PremiumUser entry
            PremiumUser::create([
                'subscriptionStartDate' => now()->toDateString(),
                'subscriptionEndDate' => now()->addDays(30)->toDateString(),
                'discountRate' => $request->input('discountRate', 0.25), // Default to 25% if not provided
                'user_id' => $request->input('user_id'),
                'payment_id' => $payment->payment_id,
            ]);

            $user = User::find($request->input('user_id'));
            $user->account_type = "premium";
            $user->save();
    
            return back()->with('success', 'Payment approved and user upgraded to premium.');
        }
    
        if ($action === 'reject') {
            // Update payment status to 'rejected'
            $payment->status = 'rejected';
            $payment->save();
    
            return back()->with('success', 'Payment rejected.');
        }
    
        return back()->with('error', 'Unable to update payment status.');
    }

   
}
