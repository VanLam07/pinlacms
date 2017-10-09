<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Cap;
use Exception;
use PlMenu;

class CapController extends BaseController
{
    
    public function __construct(Cap $cap) {
        canAccess('manage_cap');
        PlMenu::setActive('caps');
        
        $this->model = $cap;
    }
    
    public function index(Request $request){
        $caps = $this->model->getData($request->all());
        return view('admin::cap.index', ['items' => $caps]);
    }
    
    public function create(){
        return view('admin::cap.create');
    }
    
    public function store(Request $request){
        try{
            $this->model->insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors($this->model->getError());
        }
    }
    
    public function edit($id){
        $item = $this->model->findOrFail($id);
        return view('admin::cap.edit', compact('item'));
    }
    
    public function update($name, Request $request){
        $this->model->updateData($name, $request->all());
        return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
    }
    
    public function destroy($id){
        $this->model->destroy($id);
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }
    
}
