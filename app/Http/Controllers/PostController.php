<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->get();
        return response()->json($posts);
    }

    public function show(Post $post)
    {
        $post = $post->load('user');

        return response()->json($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|unique:posts,slug',
            'is_published' => 'boolean',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = Post::create($validated);

        return response()->json($post, 201);
    }

    public function userPosts(User $user)
    {
        $posts = $user->posts;
        return response()->json($posts);
    }

    // public function userPosts($userId)
    // {
    //     $posts = Post::where('user_id', $userId)->get();
    //     return response()->json($posts);
    // }
}
