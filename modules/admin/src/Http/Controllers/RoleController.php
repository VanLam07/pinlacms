<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Role;
use App\Models\Cap;
use PlMenu;
use Breadcrumb;

class RoleController extends BaseController
{
    protected $model;
    protected $cap;
    protected $cap_accept = 'manage_roles';

    public function __construct(Role $role, Cap $cap) {
        parent::__construct();
        PlMenu::setActive('roles');
        Breadcrumb::add(trans('admin::view.roles'), route('admin::role.index'));
        $this->model = $role;
        $this->cap = $cap;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $items = $this->model->getData($request->all());
        return view('admin::role.index', compact('items'));
    }
    
    public function create(){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'));
        return view('admin::role.create');
    }
    
    public function edit($id){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $item = $this->model->findOrFail($id);
        $caps = $this->cap->getData(['orderby' => 'name', 'order' => 'asc', 'per_page' => -1]);
        return view('admin::role.edit', compact('item', 'caps'));
    }
    
}
