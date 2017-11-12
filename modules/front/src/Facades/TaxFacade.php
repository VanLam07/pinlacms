<?php

namespace App\Facades\Tax;

use Illuminate\Support\Facades\Facade;

class TaxFacade extends Facade{
    public static function getFacadeAccessor() {
        return 'tax-facade';
    }
}

