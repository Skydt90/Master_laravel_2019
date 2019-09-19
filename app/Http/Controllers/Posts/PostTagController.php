<?php

namespace App\Http\Controllers\Posts;

use App\Tag;
use App\Http\Controllers\Controller;

class PostTagController extends Controller
{
    public function index($tag)
    {
        $tag = Tag::findOrFail($tag);

        return view('posts.index', [
            'posts' => $tag->blogPosts()
                ->latestWithRelations()
                ->get() 
        ]);
    }
}
