<?php

namespace App\Facades\Classes;

use App\Models\PostType;

class Post {
    
    protected $postType;

    public function __construct(PostType $post) {
        $this->postType = $post;
    }
    
    public function getTotal($type = 'post')
    {
        return $this->postType->getData($type, [
            'per_page' => -1
        ])->count();
    }
    
}

