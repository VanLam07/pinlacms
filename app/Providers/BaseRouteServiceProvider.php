<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class BaseRouteServiceProvider extends ServiceProvider
{
    protected $locale;
    
    public function __construct($app) {
        parent::__construct($app);
        $locale = request()->segment(1);
        if (!$locale || in_array($locale, langCodes())) {
            $locale = $this->app->getLocale();
        }
        $this->locale = $locale;
    }
    
}
