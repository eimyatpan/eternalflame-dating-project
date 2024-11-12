<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Date;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Place;
use App\Models\Matches;
use App\Models\PremiumUser;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function AdminDashboardCount()
    {
        $users = User::all();
        $premiumUsersCount = PremiumUser::count();
        $totalUsers = $users->count();
        $freeUsersCount = $totalUsers - $premiumUsersCount;
        $datesCount = Date::count();
        $totalMatches = Matches::all()->count();

        // Get the first day of the current month
        $startOfMonth = Carbon::now()->startOfMonth();

        // Query for daily new users from the start of the month to today
        $dailyUserData = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startOfMonth)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Query for premium users from the start of the month to today
        $premiumUserData = PremiumUser::selectRaw('DATE(subscriptionStartDate) as date, COUNT(*) as count')
            ->where('subscriptionStartDate', '>=', $startOfMonth)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Query for daily dates from the start of the month to today
        $dailyDateData = Date::selectRaw('DATE(date_time) as date, COUNT(*) as count')
            ->where('date_time', '>=', $startOfMonth)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return inertia('Admin/AdminDashboard', [
            'totalUsers' => $totalUsers,
            'totalMatches' => $totalMatches,
            'premiumUsersCount' => $premiumUsersCount,
            'freeUsersCount' => $freeUsersCount,
            'datesCount' => $datesCount,
            'dailyUserData' => $dailyUserData,
            'premiumUserData' => $premiumUserData,
            'dailyDateData' => $dailyDateData,
        ]);
    }

    public function UserTable()
    {
        // Use joins to fetch premium users and profiles with users
        $users = User::leftJoin('premiumuser', 'users.user_id', '=', 'premiumuser.user_id')
            ->leftJoin('profiles', 'users.user_id', '=', 'profiles.user_id')
            ->leftJoin('country', 'users.country_id', '=', 'country.country_id')
            ->select(
                'users.*',
                'premiumuser.user_id as premium_user',
                'profiles.profile_picture', // Select profile picture
                'country.country_name'
            )
            ->orderBy('users.user_id', 'asc') // Order by user_id descending
            ->get()
            ->map(function ($user) {
                // Ensure profile picture exists, with a fallback
                $user->profiles->profile_picture = $user->profiles->profile_picture
                    ? asset('storage/profile_pictures/' . $user->profiles->profile_picture)
                    : asset('storage/profile_pictures/default.png');
                return $user;
            });

        // Return data using Inertia
        return Inertia::render('Admin/UserTable', [
            'userData' => $users
        ]);
    }
}
