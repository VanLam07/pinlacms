<?php

namespace App\Facades\Lang;

use Illuminate\Support\Facades\Facade;

class LocaleFacade extends Facade{
    public static function getFacadeAccessor() {
        return 'pl-languages';
    }
}

