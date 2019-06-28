<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Visitor;
use PlMenu;
use Breadcrumb;

class VisitorController extends BaseController
{
    protected $cap_accept = 'view_visitors';
    protected $model;

    public function __construct() {
        parent::__construct();
        Breadcrumb::add(trans('admin::view.visitors'), route('admin::visitor.index'));
        PlMenu::setActive('visitor');
        $this->model = Visitor::class;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $visitors = Visitor::getData(array_merge($request->all(), ['per_page' => 50]));
        return view('admin::visitor.index', ['items' => $visitors]);
    }

}
