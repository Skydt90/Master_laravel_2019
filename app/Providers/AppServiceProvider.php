<?php

namespace App\Providers;

use App\BlogPost;
use App\Comment;

use App\Http\ViewComposers\ActivityComposer;

use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;

use App\Services\CounterService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        // adding an alias to blade component badge
        Blade::component('components.badge', 'badge');
        Blade::component('components.comment-form', 'commentForm');
        Blade::component('components.comment-list', 'commentList');
        Blade::component('components.updated', 'updated');
        Blade::component('components.card', 'card');
        Blade::component('components.tags', 'tags');
        Blade::component('components.errors', 'errors');

        //using a viewcomposer to create similar data to multiple views
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);
        
        //setting up observer classes
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);

        //service container config
        $this->app->singleton(CounterService::class, function ($app) {
            return new CounterService(
                $app->make('Illuminate\Contracts\Cache\Factory'),
                $app->make('Illuminate\Contracts\Session\Session'),
                env('COUNTER_TIMEOUT'));
        });

        //if this contract is requested, then instantiate the CounterService class.
        $this->app->bind('App\Contracts\CounterContract', CounterService::class);
        //$this->app->when(CounterService::class)->needs('$timeout')->give(env('COUNTER_TIMEOUT'));

        //CommentResource::withoutWrapping(); //removes 'data' wrapping on the commentresource
        Resource::withoutWrapping(); //removes 'data' wrapping on all resources
    }
}
