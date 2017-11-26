<?php

namespace App\Facades\Classes;

use App\Models\Comment as CommentModel;

class Comment {
    
    protected $comment;

    public function __construct(CommentModel $comment) {
        $this->comment = $comment;
    }
    
    public function getTotal()
    {
        return $this->comment->getData([
            'per_page' => -1
        ])->count();
    }
    
}

