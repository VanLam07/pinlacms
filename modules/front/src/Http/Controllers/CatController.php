<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Front\Http\Controllers;

use Front\Http\Controllers\BaseController;
use App\Models\Tax;
use App\Models\PostType;
use Front\Helper\FtConst;
use Breadcrumb;

/**
 * Description of CatController
 *
 * @author Pinla
 */
class CatController extends BaseController 
{
    
    public function view($slug, $id)
    {
        $tax = Tax::findByLang($id);
        if (!$tax) {
            abort(404);
        }
        Breadcrumb::add($tax->name, $tax->getLink());
        
        $posts = PostType::getData('post', [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id', 'posts.post_format',
                'posts.post_type', 'posts.views', 'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content',
                'file.id as file_id', 'file.url as file_url', 'file.title as file_name',
                'author.name as author_name'],
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'per_page' => FtConst::PER_PAGE,
            'cats' => [$id]
        ]);
        return view('front::tax', compact('tax', 'posts'));
    }
    
}
