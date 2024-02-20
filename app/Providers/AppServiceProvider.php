<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\PersonRepositoryInterface::class,
            \App\Repositories\Eloquent\PersonRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Contracts\AddressRepositoryInterface::class,
            \App\Repositories\Eloquent\AddressRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
