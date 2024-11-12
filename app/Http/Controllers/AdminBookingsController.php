<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Date;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AdminBookingsController extends Controller
{
    public function viewAllDates()
    {
        $dates = Date::with(['booking.restaurant', 'user1', 'user2'])
            ->select('date_id', 'date_time', 'user_id1', 'user_id2', 'booking_id')
            ->get()
            ->map(function ($date) {
                $countdown = Carbon::parse($date->date_time)->diffForHumans([
                    'parts' => 3,
                    'short' => true,
                    'options' => Carbon::JUST_NOW,
                ]);

                return [
                    'date_id' => $date->date_id,
                    'date_time' => $date->date_time,
                    'countdown' => $countdown,
                    'restaurant_name' => $date->booking->restaurant->restaurant_name,
                    'shop_logo' => $date->booking->restaurant->shop_logo,
                    'booked_user' => $date->user1->user_name,
                    'date_user' => $date->user2->user_name,
                ];
            });
        return Inertia::render('Admin/AdminBookings', [
            'dates' => $dates,
        ]);
    }
}
