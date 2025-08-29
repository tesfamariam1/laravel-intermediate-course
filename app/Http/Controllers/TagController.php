<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')->with('posts.user')->get();

        return response()->json([
            'data' => $tags
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string'
        ]);

        $tag = Tag::create($validated);

        return response()->json([
            'tag' => $tag,
            'message' => 'New tag created'
        ]);
    }
}
