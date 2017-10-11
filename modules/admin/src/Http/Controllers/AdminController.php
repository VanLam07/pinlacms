<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use PlMenu;

class AdminController extends Controller {
    
    public function __construct() {
        PlMenu::setActive('dashboard');
    }
    
    public function index() {
        canAccess('accept_manage');
        
        return view('admin::dashboard');
    }
    
}

