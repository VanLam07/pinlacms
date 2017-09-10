<?php

namespace App\Facades\Post;

use App\Models\PostType;

class Post{
    protected $post;
    
    public function __construct(PostType $post) {
        $this->post = $post;
    }
    
    public function query($type='post', $args = []){
        return $this->post->getData($type, $args);
    }
}

