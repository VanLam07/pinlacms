<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Lang;
use PlMenu;

class LangController extends BaseController
{
    protected $model;
    protected $cap_accept = 'manage_langs';

    public function __construct(Lang $lang) {
        PlMenu::setActive('langs');
        
        $this->model = $lang;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $langs = $this->model->getData($request->all());
        return view('admin::lang.index', ['items' => $langs]);
    }
    
    public function create(){
        canAccess($this->cap_accept);
        
        return view('admin::lang.create');
    }
    
    public function edit($id){
        canAccess($this->cap_accept);
        
        $item = $this->model->findOrFail($id);
        return view('admin::lang.edit', ['item' => $item]);
    }

}
