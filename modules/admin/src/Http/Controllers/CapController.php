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
    protected $cap_accept = 'manage_cap';

    public function __construct() {
        parent::__construct();
        Breadcrumb::add(trans('admin::view.caps'), route('admin::cap.index'));
        PlMenu::setActive('caps');
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $caps = Cap::getData($request->all());
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
            Cap::insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors(Cap::getError());
        }
    }
    
    public function edit($id){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $item = Cap::findOrFail($id);
        return view('admin::cap.edit', compact('item'));
    }
    
    public function update($name, Request $request){
        canAccess($this->cap_accept);
        
        Cap::updateData($name, $request->all());
        return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
    }
    
    public function destroy($id){
        canAccess($this->cap_accept);
        
        Cap::destroy($id);
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }
    
}
