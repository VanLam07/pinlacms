<?php

namespace Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider {
    
    public function boot() {
        
        if (!defined('ADMIN_PATH')) {
            define('ADMIN_PATH', __DIR__);
        }
        
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'admin');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
    }
    
    public function register() {
        
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations')
        ], 'migrations');
        $this->publishes([
            __DIR__.'/../config/admin.php' => config_path('admin.php')
        ], 'config');
        
        $this->mergeConfigFrom(__DIR__.'/../config/admin.php', 'admin');
        
        $this->app->register(\Admin\Providers\RouteServiceProvider::class);
        $this->app->register(\Admin\Providers\AdminComposerProvider::class);
        
        $this->app->bind('admin-view-function', 'Admin\Facades\AdView');
        
    }
    
}

