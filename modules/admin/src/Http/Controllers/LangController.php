<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Lang;
use Exception;

class LangController extends BaseController
{
    protected $model;
    
    public function __construct(Lang $lang) {
//        canAccess('manage_langs');
        
        $this->model = $lang;
    }
    
    public function index(Request $request){
        $langs = $this->model->getData($request->all());
        return view('admin::lang.index', ['items' => $langs]);
    }
    
    public function create(){
        return view('admin::lang.create');
    }
    
    public function edit($id){
        $item = $this->model->findOrFail($id);
        return view('admin::lang.edit', ['item' => $item]);
    }

}
