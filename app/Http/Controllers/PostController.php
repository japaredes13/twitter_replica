<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts = Post::with('user')->latest()->get();
        return view('home', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280', // Limitamos a 280 caracteres como en Twitter
        ]);

        Auth::user()->posts()->create([
            'content' => $request->content,
        ]);

        return redirect()->route('home')->with('success', 'Tweet publicado.');
    }
}
