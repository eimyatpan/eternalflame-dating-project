<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Country;
use App\Models\Location;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\RestaurantPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminPlaceController extends Controller
{
    public function showRestaurants()
    {
        $restaurants = Restaurant::leftJoin('locations', 'restaurants.location_id', '=', 'locations.location_id')
            ->leftJoin('country', 'locations.country_id', 'country.country_id')
            ->select('restaurants.*', 'country.country_name', 'locations.address', 'locations.city')
            ->get();
        $countries = Country::all();
        return Inertia::render('Admin/Places', [
            'countries' => $countries,
            'restaurants' => $restaurants
        ]);
    }
    public function showEachRestaurants($restaurantId)
    {
        $restaurantDetails = Restaurant::with(['location.country', 'restaurant_photos'])
            ->findOrFail($restaurantId);
        return Inertia::render('Admin/ViewPlaceDetail', [
            'restaurantDetails' => $restaurantDetails
        ]);
    }

    public function updateRestaurant(Request $request, $restaurantId)
    {
        $restaurant = Restaurant::with(['location', 'location.country', 'restaurant_photos'])
            ->findOrFail($restaurantId);

        // Validate incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'averageCost' => 'nullable|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'phone' => 'nullable|string|max:20',
            'openTime' => 'nullable',
            'closeTime' => 'nullable',
            'openDays' => 'nullable|string',
            'description' => 'nullable|string',
            'newLogo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'newPhotos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update or create the country if it doesn't exist
        $country = Country::firstOrCreate(['country_name' => $validatedData['country']]);

        // Update location details
        $restaurant->location->update([
            'country_id' => $country->country_id,
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
        ]);

        // Update the restaurant details
        $restaurant->update([
            'restaurant_name' => $validatedData['name'],
            'restaurant_phone' => $validatedData['phone'],
            'averageCost' => $validatedData['averageCost'],
            'rating' => $validatedData['rating'],
            'open_time' => $validatedData['openTime'],
            'close_time' => $validatedData['closeTime'],
            'open_days' => $validatedData['openDays'],
            'restaurant_description' => $validatedData['description'],
        ]);

        // Update logo if a new one is uploaded
        if ($request->hasFile('newLogo')) {
            // Delete the old logo if it exists
            if ($restaurant->shop_logo) {
                Storage::delete($restaurant->shop_logo);
            }
            $logoPath = $request->file('newLogo')->store('public/logos');
            $restaurant->update(['shop_logo' => Storage::url($logoPath)]);
        }

        // Handle new photos upload if present
        if ($request->hasFile('newPhotos')) {
            // Optional: Delete old photos if desired
            foreach ($restaurant->restaurant_photos as $photo) {
                Storage::delete($photo->restaurant_photo_name);
                $photo->delete();
            }

            // Save new photos
            foreach ($request->file('newPhotos') as $photo) {
                $photoPath = $photo->store('public/restaurant_photos');
                RestaurantPhoto::create([
                    'restaurant_id' => $restaurant->restaurant_id,
                    'restaurant_photo_name' => Storage::url($photoPath),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Restaurant updated successfully.');
    }


    public function goToCreate()
    {
        return Inertia::render('Admin/CreatePlace');
    }
    public function createNewRestaurant(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'restaurantName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'averageCost' => 'required|numeric',
            'rating' => 'nullable|numeric|min:0|max:5',
            'openTime' => 'required|string',
            'closeTime' => 'required|string',
            'openDays' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048', // Logo file validation
            'photos.*' => 'nullable|image|max:2048', // Multiple photo validation
        ]);

        // Handle country (assuming Country table exists)
        $country = Country::firstOrCreate(['country_name' => $validated['country']]);

        // Create or update the location
        $location = Location::create([
            'country_id' => $country->country_id,
            'city' => $validated['city'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        // Handle restaurant logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logo'); // Save logo in storage/logo
        }

        // Create the restaurant
        $restaurant = Restaurant::create([
            'restaurant_name' => $validated['restaurantName'],
            'restaurant_phone' => $validated['phone'],
            'address' => $validated['address'],
            'location_id' => $location->location_id,
            'averageCost' => $validated['averageCost'],
            'rating' => $validated['rating'],
            'open_time' => $validated['openTime'],
            'close_time' => $validated['closeTime'],
            'open_days' => $validated['openDays'],
            'restaurant_description' => $validated['description'],
            'shop_logo' => $logoPath ? Storage::url($logoPath) : null, // Store logo URL
        ]);

        // Handle multiple restaurant photos upload
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPath = $photo->store('public/restaurant_photos'); // Save in storage/restaurant_photos

                RestaurantPhoto::create([
                    'restaurant_id' => $restaurant->restaurant_id,
                    'restaurant_photo_name' => Storage::url($photoPath),
                ]);
            }
        }

        return redirect()->route('admin.restaurants')->with('success', 'Restaurant created successfully!');
    }

    public function deleteRestaurant($restaurantId)
    {
        DB::transaction(function () use ($restaurantId) {
            // Retrieve the location_id associated with the restaurant
            $locationId = DB::table('restaurants')->where('restaurant_id', $restaurantId)->value('location_id');

            // Delete associated photos
            DB::table('restaurant_photos')->where('restaurant_id', $restaurantId)->delete();

            // Delete location, if it exists
            if ($locationId) {
                DB::table('locations')->where('location_id', $locationId)->delete();
            }

            // Delete restaurant
            DB::table('restaurants')->where('restaurant_id', $restaurantId)->delete();
        });

        return redirect()->route('admin.restaurants')->with('success', 'Restaurant deleted successfully.');
    }
}
