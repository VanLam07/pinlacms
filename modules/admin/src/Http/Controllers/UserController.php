<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Role;
use Validator;

use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $user;
    protected $role;

    public function __construct(User $user, Role $role) {
        $this->user = $user;
        $this->role = $role;
    }
    
    public function index(Request $request){
        canAccess('read_users');
        
        $users = $this->user->getData($request->all());
        return view('manage.user.index', ['items' => $users]);
    }
    
    public function create(){
        canAccess('publish_users');
        
        $roles = $this->role->getData(['orderby' => 'id', 'order' => 'asc'])->lists('label', 'id'); 
        return view('manage.user.create', ['roles' => $roles]);
    }
    
    public function store(Request $request){
        canAccess('publish_users');
        
        $valid = Validator::make($request->all(), $this->user->rules());
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['slug'] = str_slug($data['name']);
        $user = $this->user->create($data);
        
        if (!isset($data['role_ids']) || !$data['role_ids']) {
            $data['role_ids'] = [$this->role->getDefaultId()];
        }
        $user->roles()->attach($data['role_ids']);
        
        return redirect()->back()->with('succ_mess', trans('manage.store_success'));
    }
    
    public function edit($id){
        canAccess('edit_my_user', $id);
        
        $item = $this->user->find($id);
        $roles = $this->role->getData(['orderby' => 'id', 'order' => 'asc'])->lists('label', 'id');
        return view('manage.user.edit', ['item' => $item, 'roles' => $roles]);
    }
    
    public function update($id, Request $request){
        canAccess('edit_my_user', $id);
        
        $valid = Validator::make($request->all(), $this->user->rules($id));
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }
        
        $data = $request->all();
        $fillable = $this->user->getFillable();
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
        
        $user = $this->user->find($id);
        if (!isset($data['role_ids']) || !$data['role_ids']) {
            $data['role_ids'] = [$this->role->getDefaultId()];
        }
        $user->roles()->sync($data['role_ids']);
        
        $data = array_only($data, $fillable);
        
        $user->update($data);
        $user->save();
        
        return redirect()->back()->with('succ_mess', trans('manage.update_success'));
    }
    
    public function destroy($id){
        canAccess('remove_my_user', $id);
        
        if(!$this->user->destroy($id)){
            return redirect()->back()->with('error_mess', trans('manage.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('manage.destroy_success'));
    }
    
    public function multiAction(Request $request){
        canAccess('manage_users');
        
        $valid = Validator::make($request->all(), [
            'action' => 'required',
            'item_ids.*' => 'required' 
        ]);
        if ($valid->fails()) {
            return redirect()->back()->withInput()->withErrors($valid->errors());
        }

        $item_ids = $request->input('item_ids');
        if (!$item_ids) {
            return redirect()->back()->withInput()->with('error_mess', trans('message.no_item'));
        }
        $action = $request->input('action');
        switch ($action) {
            case 'restore':
                $this->user->changeStatus($item_ids, User::STT_ACTIVE);
                break;
            case 'ban':
                $this->user->changeStatus($item_ids, User::STT_BANNED);
                break;
            case 'trash':
            case 'delete':
                $this->user->changeStatus($item_ids, User::STT_TRASH);
                break;
            case 'disable':
                $this->user->changeStatus($item_ids, User::STT_DISABLE);
                break;
            case 'remove':
                $this->user->destroy($item_ids);
                break;
        }
        
        return redirect()->back()->with('succ_mess', trans('message.action_success'));
    }
}
