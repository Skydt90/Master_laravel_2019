<?php

namespace App\Mail;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentPostedMarkdown extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;
    
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    
    public function build()
    {
        $subject = "Comment was posted on your {$this->comment->commentable->title} blog post";

        return $this->subject($subject)
            ->markdown('emails.posts.commented-markdown');
    }
}
