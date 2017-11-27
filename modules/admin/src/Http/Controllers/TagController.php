<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;
use PlMenu;
use Breadcrumb;

class TagController extends BaseController
{
    protected $cap_accept = 'manage_tags';

    public function __construct() {
        parent::__construct();
        PlMenu::setActive('tags');
        Breadcrumb::add(trans('admin::view.tags'), route('admin::tag.index'));
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
            Tax::insertData($request->all(), 'tag');
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (ValidationException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
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
