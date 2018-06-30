<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

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
        //Permission
        $this->app->bind('access', 'App\Facades\Access\Access');
        //Languages
        $this->app->bind('pl-languages', 'App\Facades\Lang\Locale');
        //Options
        $this->app->bind('options', 'App\Facades\Classes\Option');
        //Post
        $this->app->bind('post-facade', 'App\Facades\Classes\Post');
        //Comment
        $this->app->bind('comment-facade', 'App\Facades\Classes\Comment');
        //Tax
        $this->app->bind('tax-facade', 'App\Facades\Classes\Tax');
        //Breadcrumb
        $this->app->bind('pl-breadcrumb', 'App\Facades\Breadcrumb\Breadcrumb');
        //Menu
        $this->app->bind('pl-admin-menu', 'App\Facades\Classes\Menu');
    }
}
