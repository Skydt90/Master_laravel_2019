<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\BlogPost' => 'App\Policies\BlogPostPolicy',
        'App\User' => 'App\Policies\UserPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('home.secret', function($user) {
            return $user->is_admin;
        });

         //this will be called before any other gate check
         Gate::before(function($user, $ability) {
            //if user is admin he will be able to perform whats in the array
            if($user->is_admin && in_array($ability, ['update', 'delete'])) {
                return true;
            }
        });

        // like routes, the resource call will group all functions together in the post policy class
        //Gate::resource('post', 'App\Policies\BlogPostPolicy');

        /* Gate::define('post.update', 'App\Policies\BlogPostPolicy@update');
        Gate::define('post.delete', 'App\Policies\BlogPostPolicy@delete'); */
    }
}
