<?php

namespace App\Facades\Classes;

use App\Models\PostType;
use Admin\Facades\AdConst;
use Illuminate\Support\Facades\DB;

class Post {
    
    public function getTotal($type = 'post')
    {
        return PostType::getData($type, [
            'per_page' => -1
        ])->count();
    }
    
    public function getFeature($number = 3, $type = 'post')
    {

        $tblPrefix = DB::getTablePrefix();
        return PostType::getData($type, [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id',
                'posts.post_type', 'posts.views', 'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content',
                'file.id as file_id', 'file.url as file_url', 'file.title as file_name',
                'author.name as author_name',
                DB::raw('GROUP_CONCAT(DISTINCT(CONCAT('. $tblPrefix .'cat.id, "|", '. $tblPrefix .'cat_desc.slug, "|", '. $tblPrefix .'cat_desc.name))) as cat_names')],
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'per_page' => $number,
            'post_format' => AdConst::FORMAT_POST,
            'with_cats' => true,
            'limit' => $number,
            'is_feature' => 1,
            'orderby' => 'id',
            'order' => 'desc'
        ]);
    }
    
    public function getLatest($number = 10, $type = 'post')
    {
        $tblPrefix = DB::getTablePrefix();
        return PostType::getData($type, [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id',
                'posts.post_type', 'posts.views', 'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content',
                'file.id as file_id', 'file.url as file_url', 'file.title as file_name',
                'author.name as author_name',
                DB::raw('GROUP_CONCAT(DISTINCT(CONCAT('. $tblPrefix .'cat.id, "|", '. $tblPrefix .'cat_desc.slug, "|", '. $tblPrefix .'cat_desc.name))) as cat_names')],
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'per_page' => $number,
            'post_format' => AdConst::FORMAT_POST,
            'with_cats' => true
        ]);
    }
    
    public function getMostViews($number = 5, $type = 'post')
    {
        return PostType::getData($type, [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id', 'posts.views',
                'posts.post_type', 'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content',
                'file.id as file_id', 'file.url as file_url', 'file.title as file_name'],
            'orderby' => 'posts.views',
            'order' => 'desc',
            'limit' => $number,
            'page_name' => 'sidebar_page'
        ]);
    }

    public function getQuotes($number = 10, $type = 'post')
    {
        return PostType::getData($type, [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id',
                'posts.post_type', 'posts.views', 'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content',
                'file.id as file_id', 'file.url as file_url', 'file.title as file_name'],
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'per_page' => $number,
            'post_format' => AdConst::FORMAT_QUOTE
        ]);
    }
}

