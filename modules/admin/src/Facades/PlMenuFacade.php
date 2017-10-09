<?php

namespace Admin\Facades;

use Illuminate\Support\Facades\Facade;

class PlMenuFacade extends Facade {
    
    public static function getFacadeAccessor() {
        return 'pl-admin-menu';
    }
    
}

