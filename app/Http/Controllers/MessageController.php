<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Country;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function chatList(Request $request)
    {
        $userId = Auth::id();
        $loginUser = Auth::user();
        $loginUser->profiles->profile_picture = asset('storage/profile_pictures/' . $loginUser->profiles->profile_picture);
        $users = User::whereNot('user_id', $loginUser->user_id)->get();

        foreach ($users as $user) {
            $user->profiles->profile_picture = asset('storage/profile_pictures/' . $user->profiles->profile_picture);
        }
        // Fetch distinct chats (messages where the user is either the sender or receiver)
        $chats = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender.profiles', 'receiver.profiles']) // Eager load profiles
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(fn($message) => $this->getChatPartnerId($message, $userId)); // Keep only one message per chat partner

        // Format the chat list
        $chatList = $chats->map(fn($message) => $this->formatChat($message, $userId));

        $receiverId = $request->receiverId;
        if ($receiverId) {
            $messages = Message::where(function ($query) use ($userId, $receiverId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $receiverId);
            })->orWhere(function ($query) use ($userId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $userId);
            })->get();

            $userProfile = $messages->map(fn($message) => $this->formatChat($message, $userId));
        }

        // Send the chat list to the Vue component via Inertia
        return Inertia::render('User/UserMessages', [
            'chatList' => $chatList,
            'users'=>$users,
            'messages' => $messages ?? null,
            'userId' => $userId ?? null,
            'receiverId' => $receiverId ?? null,
            'userProfile' => $userProfile ?? null,
            'loginUser' =>$loginUser,
        ]);
    }
    public function startNewChat(Request $request)
    {
        $receiverId = $request->input('receiver_id');
        $senderId = auth()->id();
    
        // Check if a chat already exists between the two users (optional)
        $existingChat = Message::where(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $senderId)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $senderId);
        })->first();
    
        if ($existingChat) {
            return response()->json(['message' => 'Chat already exists'], 200);
        }
    
        // Create a default message
        $newMessage = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message_content' => 'Hi there! Let\'s start chatting!',
        ]);
    
        // Return the new message to the frontend
        return response()->json($newMessage, 201);
    }
    
    // Helper function to get the chat partner's user ID
    private function getChatPartnerId($message, $userId)
    {
        return $message->sender_id == $userId ? $message->receiver_id : $message->sender_id;
    }

    // Helper function to format each chat item
    private function formatChat($message, $userId)
    {
        $chatUser = $message->sender_id == $userId ? $message->receiver : $message->sender;

        // Check if profile picture exists, else use a default placeholder
        $profilePicturePath = $chatUser->profiles->profile_picture
            ? asset('storage/profile_pictures/' . $chatUser->profiles->profile_picture)
            : asset('images/default_avatar.png'); // Provide a default image if the user doesn't have a profile picture

        return [
            'id' => $chatUser->user_id,
            'name' => $chatUser->user_name,
            'image' => $profilePicturePath,
            'lastMessage' => $message->message_content
        ];
    }

    // Store a new message
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required|integer',
            'receiver_id' => 'required|integer',
            'message_content' => 'required|string'
        ]);

        $message = Message::where('sender_id', $validated['sender_id'])
            ->where('receiver_id', $validated['receiver_id'])
            ->where('message_content', '-')
            ->first();

        if ($message) {
            $message->update(['message_content' => $validated['message_content']]);
        } else {
            Message::create($validated + ['created_at' => now(), 'is_read' => false]);
        }

        return back()->with('success', 'Message sent successfully!');
    }
    

    public function viewProfile($userId) {       
        $userProfile = User::find($userId)->load([
            'profiles',     
            'hobbies',         
            'preferences',     
            'country',
            'aboutMe'          
        ]);

        $userProfile->profiles->profile_picture = asset('storage/profile_pictures/' . $userProfile->profiles->profile_picture);
        $countries = Country::select('country_id', 'country_name')->get();
        $hobbies = $userProfile->hobbies->pluck('hobby_name');
        return inertia('User/UserViewProfiles', [
            'userProfile' => $userProfile,
            'countries' => $countries,
            'hobbies' => $hobbies,
        ]);
    }    
}
