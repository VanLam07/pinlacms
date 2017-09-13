<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Cap;
use Exception;

class RoleController extends Controller
{
    protected $role;
    protected $cap;
    
    public function __construct(Role $role, Cap $cap) {
        $this->role = $role;
        $this->cap = $cap;
    }
    
    public function index(Request $request){
        $roles = $this->role->getData($request->all());
        return view('manage.role.index', ['items' => $roles]);
    }
    
    public function create(){
        return view('manage.role.create');
    }
    
    public function store(Request $request){
        try{
            $this->role->insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('manage.store_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors($this->role->getError());
        }
    }
    
    public function edit($id){
        $item = $this->role->find($id);
        $caps = $this->cap->getData(['orderby' => 'name', 'order' => 'asc', 'per_page' => -1]);
        return view('manage.role.edit', ['item' => $item, 'caps' => $caps]);
    }
    
    public function update($id, Request $request){
        try{
            $this->role->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('manage.update_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors($this->role->getError());
        }
    }
    
    public function destroy($id){
        if(!$this->role->destroy($id)){
            return redirect()->back()->with('error_mess', trans('manage.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('manage.destroy_success'));
    }
    
    public function multiAction(Request $request){
        try {
            $this->role->actions($request);
            return redirect()->back()->withInput()->with('succ_mess', trans('message.action_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
}
