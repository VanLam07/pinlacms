<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\User;
use App\Models\Role;
use Validator;
use PlMenu;
use Breadcrumb;

class UserController extends BaseController
{
    protected $cap_create = 'publish_user';
    protected $cap_edit = 'edit_user';
    protected $cap_remove = 'remove_user';

    public function __construct() {
        parent::__construct();
        Breadcrumb::add(trans('admin::view.users'), route('admin::user.index'));
        PlMenu::setActive('users');
    }
    
    public function index(Request $request){
        canAccess('view_user');
        
        $items = User::getData($request->all());
        return view('admin::user.index', compact('items'));
    }
    
    public function create(){
        canAccess($this->cap_create);
        
        Breadcrumb::add(trans('admin::view.create'));
        $roles = Role::getData(['orderby' => 'id', 'order' => 'asc', 'per_page' => -1])->pluck('label', 'id'); 
        return view('admin::user.create', compact('roles'));
    }
    
    public function store(Request $request){
        canAccess($this->cap_create);
        
        $valid = Validator::make($request->all(), User::rules());
        
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['slug'] = str_slug($data['name']);
        $user = User::create($data);
        
        if (!isset($data['role_ids']) || !$data['role_ids']) {
            $data['role_ids'] = [Role::getDefaultId()];
        }
        $user->roles()->attach($data['role_ids']);
        
        return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
    }
    
    public function edit($id){
        canAccess($this->cap_edit, $id);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $item = User::findOrFail($id);
        $roles = Role::getData(['orderby' => 'id', 'order' => 'asc', 'per_page' => -1])->pluck('label', 'id');
        return view('admin::user.edit', ['item' => $item, 'roles' => $roles]);
    }
    
    public function update($id, Request $request){
        canAccess($this->cap_edit, $id);
        
        $valid = Validator::make($request->all(), User::rules($id));
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        $data = $request->all();
        $fillable = User::getFillable();
        if (isset($data['password']) && ($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $data['slug'] = str_slug($data['name']);
        if (isset($data['birth'])) {
            $birth = $data['birth'];
            $data['birth'] = date('Y-m-d H:i:s', strtotime($birth['year'].'-'.$birth['month'].'-'.$birth['day']));
        }
        if (isset($data['image_url'])) {
            $data['image_url'] = cutImgPath($data['image_id']);
        }
        
        $user = User::findOrFail($id);
        if (!isset($data['role_ids']) || !$data['role_ids']) {
            $data['role_ids'] = [Role::getDefaultId()];
        }
        $user->roles()->sync($data['role_ids']);
        
        $data = array_only($data, $fillable);
        
        $user->update($data);
        
        return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
    }
    
    public function destroy($id){
        canAccess('remove_user', $id);
        
        User::destroyData($id);
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }

}
