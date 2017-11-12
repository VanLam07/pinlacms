<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Lang;
use PlMenu;
use Breadcrumb;

class LangController extends BaseController
{
    protected $model;
    protected $cap_accept = 'manage_langs';

    public function __construct(Lang $lang) {
        parent::__construct();
        PlMenu::setActive('langs');
        Breadcrumb::add(trans('admin::view.langs'), route('admin::lang.index'));
        $this->model = $lang;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $langs = $this->model->getData($request->all());
        return view('admin::lang.index', ['items' => $langs]);
    }
    
    public function create(){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'));
        return view('admin::lang.create');
    }
    
    public function edit($id){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $item = $this->model->findOrFail($id);
        return view('admin::lang.edit', ['item' => $item]);
    }

}
