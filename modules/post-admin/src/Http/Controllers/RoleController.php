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
    protected $cap_accept = 'manage_roles';
    protected $model;

    public function __construct() {
        parent::__construct();
        PlMenu::setActive('roles');
        Breadcrumb::add(trans('admin::view.roles'), route('admin::role.index'));
        $this->model = Role::class;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $items = Role::getData($request->all());
        return view('admin::role.index', compact('items'));
    }
    
    public function create(){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'));
        return view('admin::role.create');
    }
    
    public function redirectEdit($item)
    {
        return redirect()->route('admin::role.edit', $item->id)
                ->with('succ_mess', trans('admin::message.store_success'));
    }
    
    public function edit($id){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $item = Role::findOrFail($id);
        $caps = Cap::getData(['orderby' => 'name', 'order' => 'asc', 'per_page' => -1]);
        return view('admin::role.edit', compact('item', 'caps'));
    }
    
}
