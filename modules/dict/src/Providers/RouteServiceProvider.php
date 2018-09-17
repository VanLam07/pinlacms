<?php

namespace Dict\Providers;

use Illuminate\Support\Facades\Route;
use App\Providers\BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Dict\Http\Controllers';

    public function __construct($app) {
        parent::__construct($app);
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $allCode = langCodes();
        foreach ($allCode as $code) {
            Route::prefix($code)
                 ->middleware('web')
                 ->namespace($this->namespace)
                 ->name('dict::')
                 ->group(__DIR__.'/../../routes/web.php');
        }
    }
}

