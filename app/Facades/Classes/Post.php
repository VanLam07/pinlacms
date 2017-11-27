<?php

namespace App\Facades\Classes;

use App\Models\PostType;

class Post {
    
    public function getTotal($type = 'post')
    {
        return PostType::getData($type, [
            'per_page' => -1
        ])->count();
    }
    
}

