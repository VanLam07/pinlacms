<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Lang;
use Exception;

class LangController extends Controller
{
    protected $lang;
    
    public function __construct(Lang $lang) {
        canAccess('manage_langs');
        
        $this->lang = $lang;
    }
    
    public function index(Request $request){
        $langs = $this->lang->getData($request->all());
        return view('manage.lang.index', ['items' => $langs]);
    }
    
    public function create(){
        return view('manage.lang.create');
    }
    
    public function store(Request $request){
        try{
            $this->lang->insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('manage.store_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors($this->lang->getError());
        }
    }
    
    public function edit($id){
        $item = $this->lang->find($id);
        return view('manage.lang.edit', ['item' => $item]);
    }
    
    public function update($id, Request $request){
        try{
            $this->lang->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('manage.update_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->withErrors($this->lang->getError());
        }
    }
    
    public function destroy($id){
        if(!$this->lang->destroy($id)){
            return redirect()->back()->with('error_mess', trans('manage.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('manage.destroy_success'));
    }
    
    public function multiAction(Request $request){
        try {
            $this->lang->actions($request);
            return redirect()->back()->withInput()->with('succ_mess', trans('message.action_success'));
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
}
