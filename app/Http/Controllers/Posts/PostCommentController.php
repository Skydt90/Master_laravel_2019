<?php

namespace App\Http\Controllers\Posts;

use App\BlogPost;
use App\Events\CommentPosted;
use App\Http\Requests\StoreComment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{

    public function __contruct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function index(BlogPost $post)
    {
        //using comments relation as a method to build on query and eager load related users
        return CommentResource::collection($post->comments()->with('user')->get());
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        //calling create directly on the comments relation.
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        //calling custom event to notify users via email.
        event(new CommentPosted($comment));

        return redirect()->back()->withStatus('Comment was Created!');
    }
}
