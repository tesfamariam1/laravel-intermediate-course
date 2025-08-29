<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('tags')->get();
        return response()->json([
            'data' => $posts
        ]);
    }

    public function show(Post $post)
    {
        $post = $post->load('user');

        return response()->json($post);
    }

    public function store(Request $request)
    {
        // dd($request->tags);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|unique:posts,slug',
            'is_published' => 'boolean',
            'user_id' => 'required|exists:users,id',
            // 'tags' => 'array',
        ]);
        // dd($tags);

        $post = Post::create($validated);

        $tags = Tag::whereIn('id', json_decode($request->tags))->pluck('id');

        $post->tags()->attach($tags);
        // $tag = Tag::firstOrCreate(['name' => $request->tag]);

        // $now = now();

        // $post->tags()->attach($tag->id);

        return response()->json($post->load('tags'), 201);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            // 'tags' => 'array',
        ]);

        // dd($request->tags);
        $post->update($validated);

        $tags = Tag::whereIn('id', $request->tags)->pluck('id');

        $post->tags()->toggle($tags);
        // $post->tags()->sync($tags);

        return response()->json($post->load('tags'), 201);
    }
    public function userPosts(User $user)
    {
        $posts = $user->posts;
        return response()->json($posts);
    }

    public function detachTag(Post $post)
    {
        $post->tags()->detach();
    }
    // public function userPosts($userId)
    // {
    //     $posts = Post::where('user_id', $userId)->get();
    //     return response()->json($posts);
    // }
}
