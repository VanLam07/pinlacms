<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Role;
use App\Models\Cap;
use Illuminate\Validation\ValidationException;
use Exception;

class RoleController extends BaseController
{
    protected $model;
    protected $cap;
    
    public function __construct(Role $role, Cap $cap) {
        $this->model = $role;
        $this->cap = $cap;
    }
    
    public function index(Request $request){
        $items = $this->model->getData($request->all());
        return view('admin::role.index', compact('items'));
    }
    
    public function create(){
        return view('admin::role.create');
    }
    
    public function store(Request $request){
        try{
            $this->model->insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
    
    public function edit($id){
        $item = $this->model->findOrFail($id);
        $caps = $this->cap->getData(['orderby' => 'name', 'order' => 'asc', 'per_page' => -1]);
        return view('admin::role.edit', compact('item', 'caps'));
    }
    
    public function update($id, Request $request){
        try{
            $this->model->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
    
    public function destroy($id){
        $this->model->destroy($id);
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }
}
