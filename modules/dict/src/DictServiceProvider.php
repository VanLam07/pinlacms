<?php

namespace Dict;

use Illuminate\Support\ServiceProvider;

class DictServiceProvider extends ServiceProvider {
    
    public function boot() {
        
        if (!defined('DICT_PATH')) {
            define('DICT_PATH', __DIR__);
        }
        
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dict');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'dict');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
    }
    
    public function register() {
        
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations')
        ], 'migrations');
        
        $this->app->register(\Dict\Providers\RouteServiceProvider::class);
        $this->app->register(\Dict\Providers\DictComposerProvider::class);
    }
    
}

