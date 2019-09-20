<?php

namespace App\Listeners;

use App\Events\BlogPostPosted;
use App\Mail\BlogPostAdded;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminWhenBlogPostCreated
{
    
    public function handle(BlogPostPosted $event)
    {
        User::thatIsAdmin()->get()->map(function(User $user){

            Mail::to($user)->queue(new BlogPostAdded());
        });
    }
}
