<?php

namespace BetterDex\Providers;

use BetterDex\Dex\Combat\TypeEfficacyComparator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TypeEfficacyComparator::class);
    }
}
