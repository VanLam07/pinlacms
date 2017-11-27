<?php

namespace Admin\Http\Controllers;

use Admin\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\User;
use PlMenu;
use PlPost;
use PlComment;

class AdminController extends BaseController {
    
    protected $user;
    
    public function __construct(User $user) {
        parent::__construct();
        
        $this->user = $user;
        PlMenu::setActive('dashboard');
    }
    
    public function index() {
        canAccess('accept_manage');
        
        return view('admin::dashboard', [
            'totalPosts' => PlPost::getTotal(),
            'totalComments' => PlComment::getTotal(),
            'totalMembers' => User::getData(['per_page' => -1])->count(),
            'totalPages' => PlPost::getTotal('page'),
        ]);
    }
    
    public function search(Request $request) {
        $key = $request->get('key');
        if (!$key) {
            return redirect()->back();
        }
        
    }
    
}

