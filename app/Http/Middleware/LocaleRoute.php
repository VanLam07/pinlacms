<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class LocaleRoute {
    
    public function __construct(Application $app, Redirector $redirector, Request $request) {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }
    
    public function handle($request, Closure $next) {
        $locale = $request->segment(1);
        if (!in_array($locale, langCodes())) {
            $segments = $request->segments();
            array_unshift($segments, $this->app->getLocale());
            $this->app->setLocale($locale);
            return $this->redirector->to(implode('/', $segments));
        }
        $this->app->setLocale($locale);
        return $next($request);
    }
    
}

