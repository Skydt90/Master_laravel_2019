<?php


namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\BlogPost;
use App\User;

class ActivityComposer 
{


    public function compose(View $view)
    {
        //store most commented in cache for 10 seconds.
        $mostCommented = Cache::remember('most-commented-blog-posts', now()->addSeconds(10), function () {
            return BlogPost::mostCommented()->take(3)->get();
        });

        //store 30 mins
        $mostActive = Cache::remember('users-most-active', 30, function () {
            return User::withMostBlogPosts()->take(3)->get();
        });

        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', now()->addSeconds(10), function () {
            return User::withMostBlogPostsLastMonth()->take(3)->get();
        });

        $view->with('mostCommented', $mostCommented); 
        $view->with('mostActive', $mostActive);
        $view->with('mostActiveLastMonth', $mostActiveLastMonth);
    }

}