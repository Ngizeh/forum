<?php

namespace App\Providers;

use App\Channel;
use App\Rules\SpamFree;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
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
        // view()->composer('*', function ($view) {
        //     $view->with('channels', Cache::rememberForever('channels', function () {
        //         return Channel::all();
        //     }));
        // });

        view()->composer('*', function ($view) {
            $view->with('channels', Channel::withArchive());
        });

        Validator::extend('spamfree', 'App\Rules\SpamFree@passes');

        Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
