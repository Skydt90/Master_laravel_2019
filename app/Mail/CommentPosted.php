<?php

namespace App\Mail;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    //public properties of this class will be available 
    //inside the implementing views
    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    
    public function build()
    {

        $subject = "Comment was posted on your {$this->comment->commentable->title} blog post";

        return $this
            /* ->attach( Example with full path
                storage_path('app/public') . '/' . $this->comment->user->image->path,
                [
                    'as' => 'profile_picture.jpeg',
                    'mime' => 'image/jpeg'
                ]
            ) */
            ->attachFromStorage($this->comment->user->image->path, 'profile_picture.jpeg')
            ->subject($subject)
            ->view('emails.posts.commented');
    }
}
