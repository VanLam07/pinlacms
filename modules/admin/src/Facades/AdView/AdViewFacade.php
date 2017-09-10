<?php

namespace Admin\Facades\AdView;

use Illuminate\Support\Facades\Facade;

class AdViewFacade extends Facade {
    
    public static function getFacadeAccessor() {
        return 'admin-view-function';
    }
    
}

