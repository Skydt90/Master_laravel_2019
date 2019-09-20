<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Mail\CommentPostedMarkdown;
use Illuminate\Support\Facades\Mail;

class NotifyUsersAboutComment
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentPosted $event)
    {
        //$whenToProcess = now()->addMinutes(1);
        /* Mail::to($post->user)->send(new CommentPostedMarkdown($comment)); */
        Mail::to($event->comment->commentable->user)->queue(new CommentPostedMarkdown($event->comment));
        /* Mail::to($post->user)->later($whenToProcess, new CommentPostedMarkdown($comment)); */

        //calling a custom job class with the static dispatch
        NotifyUsersPostWasCommented::dispatch($event->comment);
    }
}
