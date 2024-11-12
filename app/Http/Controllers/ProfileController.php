<?php

namespace App\Http\Controllers;

use App\Models\AboutMe;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Photos;
use App\Models\Country;
use App\Models\Profiles;
use App\Models\Preference;
use App\Models\PremiumUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{ 
    public function myProfile()
    {
        $myProfile = Auth::user()->load([
            'profiles',
            'hobbies',
            'preferences',
            'country',
            'aboutMe'
        ]);
        $loginUser = Auth::user();
        $isPremium = PremiumUser::where('user_id',$loginUser->user_id)->exists();
        $myProfile->profiles->profile_picture = asset('storage/profile_pictures/' . $myProfile->profiles->profile_picture);
        $countries = Country::select('country_id', 'country_name')->get();
        $hobbies = $myProfile->hobbies->pluck('hobby_name');
        return inertia('User/UserProfileData', [
            'myProfile' => $myProfile,
            'countries' => $countries,
            'hobbies' => $hobbies,
            'loginUser'=>$loginUser, 'isPremium' => $isPremium,
        ]);
    }

    public function uploadProfilePicture(Request $request)
    {
        // Validate that the file is an image and specify allowed types
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:5120', // 5MB max
        ]);

        // Retrieve the authenticated user
        $user = auth()->user();

        // Retrieve the uploaded file
        $imageFile = $request->file('profile_picture');

        // Create a unique name for the file
        $imageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();

        // Store the file in the 'profile_pictures' directory within 'public'
        $imageFile->storeAs('public/profile_pictures', $imageName);

        // Find the existing profile record for the logged-in user
        $profile = Profiles::where('user_id', $user->user_id)->first();

        if ($profile) {
            // If a profile exists, update the profile picture
            $profile->update([
                'profile_picture' => $imageName,
            ]);
        } else {
            // If no profile exists, create a new profile (this case should normally not happen)
            Profiles::create([
                'profile_picture' => $imageName,
                'user_id' => $user->user_id,
            ]);
        }

        // Redirect back with success message
        return redirect()->back()->with('message', 'Profile Picture changed Successfully');
    }



    public function updateProfile(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'birthday' => 'required|date',
            'country_id' => 'required|exists:country,country_id', // Ensure the country_id is valid
            'bio' => 'nullable|string',
        ]);

        // If validation passes, update the user
        $user = User::find(Auth::user()->user_id);
        $user->user_name = $validatedData['name'];
        $user->user_gender = $validatedData['gender'];
        $user->user_dateOfBirth = $validatedData['birthday'];
        $user->country_id = $validatedData['country_id']; // Store country_id
        $user->user_age = $this->calculateAge($validatedData['birthday']);
        $user->save();

        // Update the bio in the profiles table
        $profile = $user->profiles; // Assuming there's a relationship set up in User model
        if ($profile) {
            $profile->bio = $validatedData['bio'];
            $profile->save(); // Save changes to the profile
        } else {
            return back()->withErrors(['profile' => 'Profile not found.']);
        }
        return back()->with('success', 'Profile updated successfully!'); // Redirect back with success message
    }

    public function updateHobbies(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming hobbies data
        $validatedData = $request->validate([
            'hobbies' => 'required|array',
            'hobbies.*' => 'string|max:255',
        ]);

        // Sync the hobbies with the user's current hobbies
        $user->hobbies()->delete(); // Remove old hobbies
        foreach ($validatedData['hobbies'] as $hobby) {
            $user->hobbies()->create(['hobby_name' => $hobby]);
        }

        return redirect()->back()->with('success', 'Hobbies updated successfully.');
    }

    private function calculateAge($birthday)
    {
        $birthDate = new \DateTime($birthday);
        $today = new \DateTime('today');
        $age = $today->diff($birthDate)->y;
        return $age;
    }

    public function updatePreference(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'genderPreference' => 'required|string',
            'minAgePreference' => 'required|integer|min:0',
            'maxAgePreference' => 'required|integer|gte:minAgePreference',
            'minWeightPreference' => 'required|integer|min:0',
            'maxWeightPreference' => 'required|integer|gte:minWeightPreference',
            'minHeightPreference' => 'required|integer|min:0',
            'maxHeightPreference' => 'required|integer|gte:minHeightPreference',
            'descriptionPreference' => 'nullable|string|max:500',
        ]);
        // Retrieve authenticated user
        $user = User::find(Auth::user()->user_id);
        $preferences = $user->preferences;
        if ($preferences) {
            $preferences->preference_gender = $validatedData['genderPreference'];
            $preferences->preference_min_age = $validatedData['minAgePreference'];
            $preferences->preference_max_age = $validatedData['maxAgePreference'];
            $preferences->preference_min_weight = $validatedData['minWeightPreference'];
            $preferences->preference_max_weight = $validatedData['maxWeightPreference'];
            $preferences->preference_min_height = $validatedData['minHeightPreference'];
            $preferences->preference_max_height = $validatedData['maxHeightPreference'];
            $preferences->preference_description = $validatedData['descriptionPreference'];
            $preferences->save(); // Save changes to the profile
        } else if (!$preferences) {
            $myType = Preference::create([
                'user_id' => $user->user_id,
                'preference_gender' => $validatedData['genderPreference'],
                ' preference_min_age' => $validatedData['minAgePreference'],
                'preference_max_age' => $validatedData['maxAgePreference'],
                'preference_min_weight' => $validatedData['minWeightPreference'],
                'preference_max_weight' => $validatedData['maxWeightPreference'],
                'preference_min_height' => $validatedData['minHeightPreference'],
                'preference_max_height' => $validatedData['maxHeightPreference'],
                'preference_description' => $validatedData['descriptionPreference'],
            ]);
        } else {
            return back()->withErrors(['preferences' => 'Preferences not found.']);
        }
        return back()->with('success', 'Preferences updated successfully!'); // Redirect back with success message
    }

    public function updateAppearance(Request $request)
    {
        // Validate the incoming request
        $validateData = $request->validate([
            'occupation' => 'required|string',
            'education' => 'required|string',
            'height' => 'nullable|numeric',
            'bodyType' => 'required|string',
            'eyeColor' => 'required|string',
            'hairColor' => 'required|string',
            'relationshipStatus' => 'required|string',
        ]);
        // Retrieve authenticated user
        $user = User::find(Auth::user()->user_id);
        $aboutMe = $user->aboutMe;
        if ($aboutMe) {
            $aboutMe->occupation = $validateData['occupation'];
            $aboutMe->education = $validateData['education'];
            $aboutMe->height = $validateData['height'];
            $aboutMe->body_type = $validateData['bodyType'];
            $aboutMe->eye_color = $validateData['eyeColor'];
            $aboutMe->hair_color = $validateData['hairColor'];
            $aboutMe->relationship_status = $validateData['relationshipStatus'];
            $aboutMe->save(); // Save changes to the profile
        } else if (!$aboutMe) {
            $appearance = AboutMe::create([
                'user_id' => $user->user_id,
                'occupation' => $validateData['occupation'],
                'education' => $validateData['education'],
                'height' => $validateData['height'],
                'body_type' => $validateData['bodyType'],
                'eye_color' => $validateData['eyeColor'],
                'hair_color' => $validateData['hairColor'],
                'relationship_status' => $validateData['relationshipStatus'],
            ]);
        } else {
            return back()->withErrors(['aboutMe' => 'About Me not found.']);
        }

        return back()->with('success', 'About Me updated successfully!');
    }
    public function getUserProfilePicture()
    {
        $user = Auth::user()->load('profiles');
        $userId = Auth::user()->user_id;
        if ($user && $user->profiles) {
            $profilePicture = asset('storage/profile_pictures/' . $user->profiles->profile_picture);
            return response()->json(['profile_picture' => $profilePicture, 'user_id'=>$userId,'account_type' => Auth::user()->account_type]);
        }

        return response()->json(['profile_picture' => null]);
    }
}
