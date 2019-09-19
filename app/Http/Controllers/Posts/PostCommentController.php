<?php

namespace App\Http\Controllers\Posts;

use App\BlogPost;
use App\Http\Requests\StoreComment;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{

    public function __contruct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        //calling create directly on the comments relation.
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        $whenToProcess = now()->addMinutes(1);

        /* Mail::to($post->user)->send(new CommentPostedMarkdown($comment)); */
        Mail::to($post->user)->queue(new CommentPostedMarkdown($comment));
        /* Mail::to($post->user)->later($whenToProcess, new CommentPostedMarkdown($comment)); */

        //calling a custom job class with the static dispatch
        NotifyUsersPostWasCommented::dispatch($comment);

        return redirect()->back()->withStatus('Comment was Created!');
    }
}
