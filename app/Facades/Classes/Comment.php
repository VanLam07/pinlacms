<?php

namespace App\Facades\Classes;

use App\Models\Comment as CommentModel;

class Comment {
    
    public function getTotal()
    {
        return CommentModel::getData([
            'per_page' => -1
        ])->count();
    }
    
}

