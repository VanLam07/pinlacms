<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CommentFacade extends Facade {
    
    public static function getFacadeAccessor() {
        return 'comment-facade';
    }
    
}
