<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Date;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Booking;
use App\Models\Country;
use App\Models\Restaurant;
use App\Models\PremiumUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPlaceController extends Controller
{
    public function showRestaurants()
    {
        $loginUser = auth()->user();
        $users = User::where('user_id','!=',$loginUser->user_id)->get();
        // Retrieve all restaurants with their locations, countries, and associated photos
        $restaurants = Restaurant::leftJoin('locations', 'restaurants.location_id', 'locations.location_id')
            ->leftJoin('country', 'locations.country_id', 'country.country_id')
            ->leftJoin('restaurant_photos', 'restaurants.restaurant_id', '=', 'restaurant_photos.restaurant_id')
            ->select('restaurants.*', 'locations.city', 'locations.address', 'country.country_name', 'restaurant_photos.restaurant_photo_name')
            ->get()
            ->groupBy('restaurant_id')
            ->map(function ($groupedRestaurants) {
                // Get the first instance of restaurant details (as they are the same in each group)
                $restaurant = $groupedRestaurants->first();
                // Collect all photo names into an array for this restaurant
                $restaurant->restaurant_photos = $groupedRestaurants->pluck('restaurant_photo_name')->filter()->all();
                return $restaurant;
            })
            ->values();
    
        $isPremium = PremiumUser::where('user_id',$loginUser->user_id)->exists();    
        // Return the data to the Inertia component
        return Inertia::render('User/UserDatePlaces', [
            'restaurants' => $restaurants,
            'loginUser' => $loginUser,
            'users' =>$users,
            'isPremium'=>$isPremium,
        ]);
    }

    public function bookDate(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required',
            'booking_date' => 'required',
            'booking_time' => 'required',
            'user_id2' => 'required', // The user with whom the date is booked
        ]);
        $loginUserId = Auth::id();
        $bookingDateTime = Carbon::parse("{$request->booking_date} {$request->booking_time}");
        // Step 1: Create a new Booking record
        $booking = Booking::create([
            'user_id' => $loginUserId,
            'restaurant_id' => $request->restaurant_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
        ]);

        // Step 2: Create a new Date record with the booking details
        Date::create([
            'user_id1' => $loginUserId,
            'user_id2' => $request->user_id2,
            'booking_id' => $booking->booking_id,
            'date_time' => $bookingDateTime,
        ]);

        return redirect()->route('user.datePlaces')->with('message', 'Booking successful!');
    }
    
}
