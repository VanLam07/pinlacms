<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Subscribe;
use PlMenu;
use Breadcrumb;

class SubscribeController extends BaseController
{
    protected $cap_accept = 'manage_subscribes';
    protected $model;

    public function __construct() {
        parent::__construct();
        Breadcrumb::add(trans('admin::view.visitors'), route('admin::visitor.index'));
        PlMenu::setActive('visitor');
        $this->model = Subscribe::class;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        return 'subscribe';
    }

}
