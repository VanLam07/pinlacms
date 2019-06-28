<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use App\Models\PostType;
use Illuminate\Support\Facades\DB;
use Front\Helper\FtConst;
use Admin\Facades\AdConst;

/**
 * Description of TagController
 *
 * @author Pinla
 */
class TagController extends Controller 
{
    
    public function view($slug, $id)
    {
        $tax = Tax::findByLang($id);
        if (!$tax || $tax->status != AdConst::STT_PUBLISH) {
            abort(404);
        }
        $tblPrefix = DB::getTablePrefix();
        $posts = PostType::getData('post', [
            'fields' => ['posts.id', 'posts.author_id', 'posts.created_at', 'posts.thumb_id', 'posts.post_format',
                'posts.post_type', 'posts.views', 'pd.title', 'pd.slug', 'pd.excerpt', 'pd.content',
                'file.id as file_id', 'file.url as file_url', 'file.title as file_name',
                'author.name as author_name',
                DB::raw('GROUP_CONCAT(DISTINCT(CONCAT('. $tblPrefix .'cat.id, "|", '. $tblPrefix .'cat_desc.slug, "|", '. $tblPrefix .'cat_desc.name))) as cat_names')],
            'orderby' => 'posts.created_at',
            'order' => 'desc',
            'per_page' => FtConst::PER_PAGE,
            'tags' => [$id],
            'with_cats' => true
        ]);
        return view('front::tax', compact('tax', 'posts'));
    }
    
}
