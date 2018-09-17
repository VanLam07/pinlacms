<?php

namespace Dict\Http\Controllers\Admin;

use Admin\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Dict\Models\DictEnVn;
use PlMenu;
use Breadcrumb;

class DictController extends BaseController {
    
    protected $cap_accept = 'manage_dictionaries';
    protected $model;
    
    public function __construct() {
        parent::__construct();
        Breadcrumb::add(trans('dict::view.man_dictionaries'), route('dict::admin.index'));
        PlMenu::setActive('dictionary');
        $this->model = DictEnVn::class;
    }
    
    public function index(Request $request)
    {
        $items = DictEnVn::getData($request->all());
        return view('dict::manage.index', compact('items'));
    }
    
    public function create()
    {
        return view('dict::manage.create', ['item' => null]);
    }
    
    public function edit($id)
    {
        $item = DictEnVn::findOrFail($id);
        return view('dict::manage.edit', compact('item'));
    }
    
    public function redirectEdit($item)
    {
        return redirect()->route('dict::admin.edit', $item->id)
                ->with('succ_mess', trans('admin::message.store_success'));
    }
    
}

