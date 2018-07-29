<?php

namespace Admin\Http\Controllers;

use Admin\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\PostType;
use App\Models\Tax;
use App\User;
use PlMenu;
use PlPost;
use PlComment;
use Breadcrumb;

class AdminController extends BaseController {
    
    protected $user;
    
    public function __construct(User $user) {
        parent::__construct();
        
        $this->user = $user;
    }
    
    public function index() {
        PlMenu::setActive('dashboard');
        canAccess('accept_manage');
        
        return view('admin::dashboard', [
            'totalPosts' => PlPost::getTotal(),
            'totalComments' => PlComment::getTotal(),
            'totalMembers' => User::getData(['per_page' => -1])->count(),
            'totalPages' => PlPost::getTotal('page'),
        ]);
    }
    
    public function search(Request $request) {
        Breadcrumb::add(trans('admin::view.search'));
        $data = $request->all();
        $posts = PostType::getData('post', array_merge($data, ['page_name' => 'post_page']));
        $pages = PostType::getData('page', array_merge($data, ['page_name' => 'page']));
        $cats = Tax::getData('cat', array_merge($data, ['page_name' => 'cat_page']));
        $tags = Tax::getData('tag', array_merge($data, ['page_name' => 'tag_page']));
        return view('admin::search', compact('posts', 'pages', 'cats', 'tags'));
    }
    
}

