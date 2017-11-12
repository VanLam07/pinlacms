<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use PlMenu;

class AdminController extends Controller {
    
    public function __construct() {
        
    }
    
    public function index() {
        canAccess('accept_manage');
        
        PlMenu::setActive('dashboard');
        
        return view('admin::dashboard');
    }
    
}

