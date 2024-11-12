<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Participations;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchingController extends Controller
{
    public function findMatches($userId)
    {
        // Get the current user's profile, preferences, and country
        $user = User::with(['profiles', 'preferences', 'country', 'hobbies'])->findOrFail($userId);
        $user->profiles->profile_picture = asset('storage/profile_pictures/' . $user->profiles->profile_picture);
    
        // Filter potential matches based on preferences and country
        $matches = User::where('user_gender', $user->preferences->preference_gender)
            ->whereBetween('user_age', [$user->preferences->preference_min_age, $user->preferences->preference_max_age])
            ->where('user_id', '!=', $userId) // Exclude the user themselves
            ->with(['profiles', 'hobbies', 'country']) // Load related models
            ->get()
            ->map(function ($match) {
                // Generate profile picture URL for each match
                $match->profiles->profile_picture = asset('storage/profile_pictures/' . $match->profiles->profile_picture);
                return $match;
            });
    
        // Calculate and store raw compatibility score for sorting
        $sortedMatches = $matches->map(function ($match) use ($user) {
            $rawScore = $this->calculateRawCompatibility($user, $match);
            $match->rawCompatibilityScore = $rawScore; // Store raw score for sorting
            $match->compatibilityScore = $this->formatScore($rawScore); // Store formatted score for display
            return $match;
        })->sortByDesc('rawCompatibilityScore')->values(); // Sort by raw score in descending order
        // Automatically store matches
        $this->storeMatch($userId, $sortedMatches);
    
        // Return matches using Inertia
        return Inertia::render('User/UserMatching', [
            'myProfile' => $user,
            'matches' => $sortedMatches
        ]);
    }
    
    private function calculateRawCompatibility($user, $match)
    {
        $compatibilityScore = 0;
    
        // 1. Calculate hobby similarity score
        $commonHobbies = array_intersect(
            $user->hobbies->pluck('hobby_name')->toArray(),
            $match->hobbies->pluck('hobby_name')->toArray()
        );
        $maxHobbies = max(count($user->hobbies), count($match->hobbies));
        $hobbyScore = ($maxHobbies > 0) ? (count($commonHobbies) / $maxHobbies) : 0;
        $compatibilityScore += $hobbyScore * 0.5; // 50% weight for hobbies
    
        // 2. Calculate age similarity score
        $ageDifference = abs($user->user_age - $match->user_age);
        $ageScore = 1 / (1 + $ageDifference); // Smaller difference = higher score
        $compatibilityScore += $ageScore * 0.3; // 30% weight for age
    
        // 3. Same country bonus (0.2 weight)
        if ($user->country_id == $match->country_id) {
            $compatibilityScore += 0.2;
        }
    
        // Ensure the final score is between 0 and 1, then convert to percentage
        return min($compatibilityScore, 1) * 100;
    }
    
    private function formatScore($rawScore)
    {
        // Format to remove decimal places for whole numbers, keep two for others
        return (fmod($rawScore, 1) == 0) 
            ? number_format($rawScore, 0) . '%' 
            : number_format($rawScore, 2) . '%';
    }
    

    private function storeMatch($userId, $sortedMatches)
    {
        foreach ($sortedMatches as $match) {
            // Create a new match entry if it doesn't already exist
            $existingMatch = Participations::where('user_id', $userId)
                ->whereHas('matches', function ($query) use ($match) {
                    $query->where('user_id', $match->id);
                })->exists();

            if (!$existingMatch) {
                // Create a new match entry
                $matchEntry = Matches::create(['match_date' => now()]);

                // Insert both users into the Participation table
                Participations::create([
                    'match_id' => $matchEntry->match_id,
                    'user_id' => $userId,
                    'participation_date' => now()
                ]);

                Participations::create([
                    'match_id' => $matchEntry->match_id,
                    'user_id' => $match->user_id,
                    'participation_date' => now()
                ]);
            }
        }
    }
}
