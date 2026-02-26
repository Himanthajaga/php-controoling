<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'user_id' => 'required|exists:users,id',
        ], [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'content.required' => 'The content field is required.',
            'content.min' => 'The content must be at least 10 characters.',
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'The selected user does not exist.',
        ]);

        // Create the post
        Post::create($validated);

        return redirect('/posts')->with('success', 'Post created successfully!');
    }
}
