<?php

namespace Front;

use Illuminate\Support\ServiceProvider;

class FrontServiceProvider extends ServiceProvider 
{
    
    public function boot() 
    {
        if (!defined('FRONT_DIR')) {
            define('FRONT_DIR', __DIR__);
        }
        
        $this->loadViewsFrom(__DIR__.'/../resources/view', 'front');
        $this->loadTranslationsFrom(__DIR__.'/../resources/view', 'front');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
    
    public function register()
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations')
        ], 'migration');
        
        $this->app->register('\Front\Providers\RouteServiceProvider');
        $this->app->register('\Front\Providers\FrontComposerProvider');
        
        $this->app->bind('post-facade', '\Front\Facades\Post');
        $this->app->bind('tax-facade', '\Front\Facades\Tax');
    }
    
}

