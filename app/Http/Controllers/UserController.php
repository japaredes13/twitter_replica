<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    
    public function searchUsers(Request $request){
        $query = $request->get('query');
        $users = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->get();
                    
        foreach ($users as $user) {
            $user->is_following = Auth::user()->followings->contains($user->id);
        }

        return response()->json(["users" => $users]);
    }

    public function toggleFollow($userId){
        $user = Auth::user();
        $isFollowing = $user->followings()->where('following_id', $userId)->exists();

        if ($isFollowing) {
            $user->followings()->detach($userId);
        } else {
            $user->followings()->attach($userId);
        }

        $followersCount = $user->followers()->count();
        $followingCount = $user->followings()->count();

        return response()->json([
            'is_following' => !$isFollowing,
            'followers_count' => $followersCount,
            'following_count' => $followingCount,
        ]);
    }

}
