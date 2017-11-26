<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PlPost;

class PageController extends Controller
{
    public function index()
    {
        return view('front::index');
    }
}
