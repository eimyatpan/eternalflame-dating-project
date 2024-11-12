<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function showProfiles()
    {
        $users = User::leftJoin('country', 'users.country_id', '=', 'country.country_id')
            ->leftJoin('profiles', 'users.user_id', '=', 'profiles.user_id')
            ->select('users.*', 'country.country_name', 'profiles.profile_picture') // Select all user fields and the country name
            ->get()
            ->map(function ($user) {
                // Ensure profile picture exists, with a fallback
                $user->profiles->profile_picture = $user->profiles->profile_picture
                    ? asset('storage/profile_pictures/' . $user->profiles->profile_picture)
                    : asset('storage/profile_pictures/default.png');
                return $user;
            });
        return inertia('HomePage', ['users' => $users]);
    }
    public function profiles()
    {
        $users = User::leftJoin('country', 'users.country_id', '=', 'country.country_id')
        ->leftJoin('profiles', 'users.user_id', '=', 'profiles.user_id')
        ->select('users.*', 'country.country_name', 'profiles.profile_picture') // Select all user fields and the country name
        ->get()
        ->map(function ($user) {
            // Ensure profile picture exists, with a fallback
            $user->profiles->profile_picture = $user->profiles->profile_picture
                ? asset('storage/profile_pictures/' . $user->profiles->profile_picture)
                : asset('storage/profile_pictures/default.png');
            return $user;
        });
        $countries = Country::all();
        return inertia('ProfilePage', ['users' => $users, 'countries' => $countries]);
    }

}
