<?php

namespace Admin\Providers;

use Illuminate\Support\ServiceProvider;

class AdminComposerProvider extends ServiceProvider {
    
    public function boot() {
        view()->composer('admin::parts.menubar', 'Admin\Composers\MenuComposer');
        view()->composer('*', 'Admin\Composers\LangComposer');
    }
    
    public function register() {
        
    }
    
}

