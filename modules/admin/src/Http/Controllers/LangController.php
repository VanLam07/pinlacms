<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;

use Admin\Http\Controllers\BaseController;
use App\Models\Lang;
use PlMenu;
use Breadcrumb;
use Admin\Facades\AdConst;

class LangController extends BaseController
{
    
    protected $cap_accept = 'manage_langs';

    public function __construct(Lang $lang) {
        parent::__construct();
        PlMenu::setActive('langs');
        Breadcrumb::add(trans('admin::view.langs'), route('admin::lang.index', ['status' => AdConst::STT_PUBLISH]));
        $this->model = $lang;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $langs = Lang::getData($request->all());
        return view('admin::lang.index', ['items' => $langs]);
    }
    
    public function create(){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'));
        return view('admin::lang.create');
    }
    
    public function redirectEdit($item)
    {
        return redirect()->route('admin::lang.edit', $item->code)
                ->with('succ_mess', trans('admin::message.store_success'));
    }
    
    public function edit($id){
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $item = Lang::findOrFail($id);
        return view('admin::lang.edit', ['item' => $item]);
    }

}
