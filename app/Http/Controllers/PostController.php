<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'content' => 'required|string|max:280', // Limitamos a 280 caracteres como en Twitter
        ]);

        $user = Auth::user();
        $user->posts()->create([
            'content' => $request->content,
        ]);

        return redirect()->route('home', ["user" => $user->name])->with('success', 'Tweet publicado.');
    }
}
