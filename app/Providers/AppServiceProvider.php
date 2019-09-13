<?php

namespace App\Providers;

use App\Services\CounterService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        // adding an alias to blade component badge
        Blade::component('components.badge', 'badge');
        Blade::component('components.updated', 'updated');
        Blade::component('components.card', 'card');
        Blade::component('components.tags', 'tags');

        //service container config
        $this->app->singleton(CounterService::class, function ($app) {
            return new CounterService(env('COUNTER_TIMEOUT'));
        });
    }
}
