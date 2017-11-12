<?php

namespace Admin\Http\Controllers;

use Admin\Http\Controllers\BaseController;
use PlMenu;
use Breadcrumb;

class AdminController extends BaseController {
    
    public function __construct() {
        parent::__construct();
        
        PlMenu::setActive('dashboard');
    }
    
    public function index() {
        canAccess('accept_manage');
        
        return view('admin::dashboard');
    }
    
}

