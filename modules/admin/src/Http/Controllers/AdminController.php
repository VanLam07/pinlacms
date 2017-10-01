<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;

class AdminController extends Controller {
    
    public function __construct() {
        
    }
    
    public function index() {
        return view('admin::dashboard');
    }
    
}

