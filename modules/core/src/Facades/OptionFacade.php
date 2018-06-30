<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OptionFacade extends Facade{
    public static function getFacadeAccessor() {
        return 'options';
    }
}

