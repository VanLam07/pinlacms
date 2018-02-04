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
use Front\Helper\FtConst;
use PlOption;

/**
 * Description of CatController
 *
 * @author Pinla
 */
class CatController extends Controller 
{
    
    public function view($id)
    {
        $tax = Tax::findByLang($id);
        if (!$tax) {
            abort(404);
        }
        
        $posts = PostType::getData('post', [
            'per_page' => PlOption::get('front_per_page', null, FtConst::PER_PAGE),
            'cats' => [$id]
        ]);
        return view('front::tax', compact('tax', 'posts'));
    }
    
}
