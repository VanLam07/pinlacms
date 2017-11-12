<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Cap;
use Exception;
use PlMenu;
use Breadcrumb;

class CapController extends BaseController
{
    protected $model;
    protected $cap_accept = 'manage_cap';

    public function __construct(Cap $cap) {
        parent::__construct();
        Breadcrumb::add(trans('admin::view.caps'), route('admin::cap.index'));
        PlMenu::setActive('caps');
        $this->model = $cap;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $caps = $this->model->getData($request->all());
        return view('admin::cap.index', ['items' => $caps]);
    }
    
    public function create(){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'), route('admin::cap.create'));
        return view('admin::cap.create');
    }
    
    public function store(Request $request){
        canAccess($this->cap_accept);
        
        try{
            $this->model->insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors($this->model->getError());
        }
    }
    
    public function edit($id){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $item = $this->model->findOrFail($id);
        return view('admin::cap.edit', compact('item'));
    }
    
    public function update($name, Request $request){
        canAccess($this->cap_accept);
        
        $this->model->updateData($name, $request->all());
        return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
    }
    
    public function destroy($id){
        canAccess($this->cap_accept);
        
        $this->model->destroy($id);
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }
    
}
