<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use App\Exceptions\PlException;
use PlMenu;
use Breadcrumb;
use Admin\Facades\AdConst;

class TagController extends BaseController
{
    protected $cap_accept = 'manage_tags';
    protected $model;

    public function __construct() {
        parent::__construct();
        PlMenu::setActive('tags');
        Breadcrumb::add(trans('admin::view.tags'), route('admin::tag.index', ['status' => AdConst::STT_PUBLISH]));
        $this->model = Tax::class;
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $tags = Tax::getData('tag', $data);
        return view('admin::tag.index', ['items' => $tags]);
    }

    public function create() {
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'));
        return view('admin::tag.create');
    }
    
    public function store(Request $request) {
        canAccess($this->cap_accept);
        
        DB::beginTransaction();
        try {
            $item = Tax::insertData($request->all(), 'tag');
            DB::commit();
            return redirect()->route('admin::tag.edit', $item->id)
                    ->with('succ_mess', trans('admin::message.store_success'));
        } catch (PlException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }

    public function edit($id, Request $request) {
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        $item = Tax::findByLang($id, ['taxs.*', 'td.*'], $lang);
        return view('admin::tag.edit', compact('item', 'lang'));
    }

}
