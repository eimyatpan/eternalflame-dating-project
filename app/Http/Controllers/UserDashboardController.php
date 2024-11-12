<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Booking;
use App\Models\Date;
use App\Models\Profiles;
use App\Models\PremiumUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function potentialProfiles()
    {
        $user = Auth::user();
        $startAge = $user->user_age - 5;
        $endAge = $user->user_age + 5;

        $premiumUsers = PremiumUser::join('users', 'premiumuser.user_id', '=', 'users.user_id') // Join users
            ->whereNot('premiumuser.user_id', $user->user_id) // Exclude the logged-in user
            ->join('profiles', 'profiles.user_id', '=', 'users.user_id') // Join profiles through users
            ->whereBetween('users.user_age', [$startAge, $endAge]) // Age range filter
            ->select('users.user_name', 'users.user_age', 'profiles.profile_picture', 'premiumuser.*') // Select fields
            ->get();

        foreach ($premiumUsers as $premiumUser) {
            $premiumUser->profile_picture = asset('storage/profile_pictures/' . $premiumUser->profile_picture);
        }
        $date = Date::select(
            'date.date_id',
            'date.date_time',
            'restaurants.restaurant_name as restaurantName',
            'restaurants.shop_logo as restaurantPhoto',
            'users.user_name as bookedUser',
        )
            ->join('booking', 'date.booking_id', '=', 'booking.booking_id')
            ->join('restaurants', 'booking.restaurant_id', '=', 'restaurants.restaurant_id')
            ->join('users', 'booking.user_id', '=', 'users.user_id')
            ->where('booking.user_id', $user->user_id)
            ->orWhere(function ($subQuery) use ($user) {
                $subQuery->where('user_id1', $user->user_id)
                    ->orWhere('user_id2', $user->user_id);
            })
            ->orderBy('date.date_time', 'asc')
            ->get();

        $users = User::whereNot('user_id', $user->user_id)
            ->whereBetween('user_age', [$startAge, $endAge])
            ->get();

        foreach ($users as $user) {
            $user->profiles->profile_picture = asset('storage/profile_pictures/' . $user->profiles->profile_picture);
        }

        return inertia('User/UserDashboard', ['users' => $users, 'profileData' => $user, 'premiumUsers' => $premiumUsers, 'dates' => $date]);
    }
}
