<?php

namespace App\Jobs;

use App\Comment;
use App\Mail\CommentPostedOnPostWatched;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

//This is a custom class that implements custom background logic to be executed in the queue
class NotifyUsersPostWasCommented implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;  //amount of tries before moving to failed
    public $timeout = 30;   //maximum number of seconds that jobs can run
    public $comment;
    
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        User::thatHasCommentedOnPost($this->comment->commentable)
            ->get()

            //filter away the user, who has posted the comment
            ->filter(function (User $user) {

                return $user->id !== $this->comment->user_id;

            })->map(function (User $user) {
                Mail::to($user)->send(
                    new CommentPostedOnPostWatched($this->comment, $user)
                );
            });
    }
}
