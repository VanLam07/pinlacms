<?php

namespace Front\Providers;

use Illuminate\Support\ServiceProvider;

class FrontComposerProvider extends ServiceProvider
{
    public function boot() {
        view()->composer('front::*', 'Front\Composers\FrontComposer');
    }
}
