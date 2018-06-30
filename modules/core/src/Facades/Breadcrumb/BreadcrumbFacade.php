<?php

namespace App\Facades\Breadcrumb;

use Illuminate\Support\Facades\Facade;

class BreadcrumbFacade extends Facade {
    
    public static function getFacadeAccessor() {
        return 'pl-breadcrumb';
    }
    
}

