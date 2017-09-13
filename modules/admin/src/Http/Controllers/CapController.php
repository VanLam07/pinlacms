<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Cap;
use Exception;

class CapController extends Controller
{
    protected $cap;
    
    public function __construct(Cap $cap) {
        $this->cap = $cap;
    }
    
    public function index(Request $request){
        $caps = $this->cap->getData($request->all());
        return view('admin::cap.index', ['items' => $caps]);
    }
    
    public function create(){
        return view('admin::cap.create');
    }
    
    public function store(Request $request){
        try{
            $this->cap->insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('manage.store_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors($this->cap->getError());
        }
    }
    
    public function edit($id){
        $item = $this->cap->find($id);
        $highers = $this->cap->getData(['orderby' => 'name', 'exclude' => [$id], 'per_page' => -1])->lists('name', 'name');
        $highers->prepend(trans('manage.selection'), '');
        return view('manage.cap.edit', ['item' => $item, 'highers' => $highers]);
    }
    
    public function update($id, Request $request){
        try{
            $this->cap->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('manage.update_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors($this->cap->getError());
        }
    }
    
    public function destroy($id){
        if(!$this->cap->destroy($id)){
            return redirect()->back()->with('error_mess', trans('manage.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('manage.destroy_success'));
    }
    
    public function multiAction(Request $request){
        return response()->json($this->cap->actions($request));
    }
}
