<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;

class AdminController extends Controller {
    
    public function __construct() {
        canAccess('accept_manage');
    }
    
    public function index() {
        return view('admin::dashboard');
    }
    
}

