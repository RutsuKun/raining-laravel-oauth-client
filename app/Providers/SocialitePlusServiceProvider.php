<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\RainingDreamsProvider;
use Socialite;

class SocialitePlusServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend('rainingdreams',
        function ($app) use ($socialite) {
            $config = $app['config']['services.rainingdreams'];
            return $socialite->buildProvider(RainingDreamsProvider::class, $config);
        }); 
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
