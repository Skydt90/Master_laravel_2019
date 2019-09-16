<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Http\Requests\StoreComment;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{

    public function __contruct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        //calling create directly on the comments relation.
        $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        $request->session()->flash('success', 'Comment was created!');

        return redirect()->back();
    }
}
