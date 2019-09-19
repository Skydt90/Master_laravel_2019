<?php

namespace App\Mail;

use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentPostedOnPostWatched extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $comment;
    public $user;

    public function __construct(Comment $comment, User $user)
    {
        $this->comment = $comment;
        $this->user = $user;
    }

    public function build()
    {
        return $this->markdown('emails.posts.comment-posted-on-watched');
    }
}
