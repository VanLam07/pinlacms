<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MenuFacade extends Facade {
    
    public static function getFacadeAccessor() {
        return 'pl-admin-menu';
    }
    
}

