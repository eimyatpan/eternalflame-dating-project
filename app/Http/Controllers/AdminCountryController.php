<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class AdminCountryController extends Controller
{
    public function showCountry()
    {
        $countries = Country::get();
        $allCountries = Country::all();
        $totalCountries = $allCountries->count();

        foreach ($countries as $c) {
            $c->country_image = asset('storage/' . $c->country_image);
        }

        return inertia('Admin/Countries', ['countriesData' => $countries, 'totalCountries' => $totalCountries]);
    }

    public function createCountry(Request $request)
    {

        $validated = $request->validate([
            'country_name' => 'required|string|max:255',
            'country_image' => 'nullable|mimes:jpg,png,jpeg,webp,svg',
        ]);

        $imageFile = $request->country_image;
        $imageName = uniqid() . "." . $imageFile->getClientOriginalExtension();
        $imageFile->storeAs('public', $imageName);

        Country::create([
            'country_name' => $request->country_name,
            'country_image' => $imageName,
        ]);
        return redirect()->back()->with('message', 'Country added Successfully');
    }
    public function deleteCountry(Country $country)
    {
        $country->delete();

        // Redirect back to the country list with success message
        return redirect()->back()->with('success', 'Country deleted successfully!');
    }
}
