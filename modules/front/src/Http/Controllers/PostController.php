<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostType;
use App\Models\Tax;
use PlOption;
use Front\Helper\FtConst;

class PostController extends Controller
{
    public function view($id)
    {
        $post = PostType::findByLang($id);
        $post->increment('views');
        if (!$post) {
            abort(404);
        }
        
        return view('front::post', compact('post'));
    }
}
