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
    
    public function getFeature($number = 3, $type = 'post')
    {
        return PostType::getData($type, [
            'per_page' => $number,
            'is_feature' => 1
        ]);
    }
    
    public function getLatest($number = 10, $type = 'post')
    {
        return PostType::getData($type, [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id',
                'posts.post_type', 'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content'],
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'per_page' => $number
        ]);
    }
    
    public function getMostViews($number = 5, $type = 'post')
    {
        return PostType::getData($type, [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id', 'posts.views',
                'posts.post_type', 'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content'],
            'orderby' => 'posts.views',
            'order' => 'desc',
            'per_page' => $number,
            'page_name' => 'sidebar_page'
        ]);
    }
}

