<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use PlMenu;

class AdminController extends Controller {
    
    public function __construct() {
        canAccess('accept_manage');
    }
    
    public function index() {
        PlMenu::setActive('dashboard');
        
        return view('admin::dashboard');
    }
    
}

