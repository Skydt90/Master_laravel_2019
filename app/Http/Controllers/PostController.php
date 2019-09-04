<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\Request;

class PostController extends Controller
{
    
    public function index()
    {
        return view('posts.index', ['posts' => BlogPost::all()]);
    }

    public function show($id)
    {
        return view('posts.show', ['post' => BlogPost::findOrFail($id)]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $post = new BlogPost();
        $post->title = $request->title;
        $post->content = $request->content;

        if($post->save()) {
            $request->session()->flash('success', 'Post was created!');
            return redirect()->route('post.show', ['post' => $post->id]);
        }

    }
}
