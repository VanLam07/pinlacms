<?php

namespace Front\Http\Controllers;

use App\Http\Controllers\Controller;
use Breadcrumb;

class BaseController extends Controller 
{
    public function __construct() {
        Breadcrumb::add(trans('front::view.home_page'), route('front::home'));
    }
}
