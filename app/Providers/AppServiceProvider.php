<?php

namespace BetterDex\Providers;

use BetterDex\Dex\Combat\TypeEfficacyComparator;
use BetterDex\Dex\Species\PokemonStatRepository;
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
        $this->app->singleton(PokemonStatRepository::class);
    }
}
