<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\User;
use App\Models\Role;
use Validator;
use PlMenu;

class UserController extends BaseController
{
    protected $model;
    protected $role;

    public function __construct(User $user, Role $role) {
        $this->model = $user;
        $this->role = $role;
        
        PlMenu::setActive('users');
    }
    
    public function index(Request $request){
        canAccess('view_user');
        
        $items = $this->model->getData($request->all());
        return view('admin::user.index', compact('items'));
    }
    
    public function create(){
        canAccess('publish_user');
        
        $roles = $this->role->getData(['orderby' => 'id', 'order' => 'asc', 'per_page' => -1])->pluck('label', 'id'); 
        return view('admin::user.create', compact('roles'));
    }
    
    public function store(Request $request){
        $valid = Validator::make($request->all(), $this->model->rules());
        
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['slug'] = str_slug($data['name']);
        $user = $this->model->create($data);
        
        if (!isset($data['role_ids']) || !$data['role_ids']) {
            $data['role_ids'] = [$this->role->getDefaultId()];
        }
        $user->roles()->attach($data['role_ids']);
        
        return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
    }
    
    public function edit($id){
        $item = $this->model->findOrFail($id);
        $roles = $this->role->getData(['orderby' => 'id', 'order' => 'asc', 'per_page' => -1])->pluck('label', 'id');
        return view('admin::user.edit', ['item' => $item, 'roles' => $roles]);
    }
    
    public function update($id, Request $request){
//        canAccess('edit_my_user', $id);
        
        $valid = Validator::make($request->all(), $this->model->rules($id));
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        $data = $request->all();
        $fillable = $this->model->getFillable();
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
        
        $user = $this->model->findOrFail($id);
        if (!isset($data['role_ids']) || !$data['role_ids']) {
            $data['role_ids'] = [$this->role->getDefaultId()];
        }
        $user->roles()->sync($data['role_ids']);
        
        $data = array_only($data, $fillable);
        
        $user->update($data);
        
        return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
    }
    
    public function destroy($id){
//        canAccess('remove_my_user', $id);
        
        $this->model->destroyData($id);
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }
    
    public function multiActions(Request $request){
//        canAccess('manage_users');
        return parent::multiActions($request);
    }
}
