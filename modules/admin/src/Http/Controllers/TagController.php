<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;

class TagController extends BaseController
{
    protected $model;

    public function __construct(Tax $tag) {
//        canAccess('manage_tags');

        $this->model = $tag;
    }

    public function index(Request $request) {
        $data = $request->all();
        $tags = $this->model->getData('tag', $data);
        return view('admin::tag.index', ['items' => $tags]);
    }

    public function create() {
        return view('admin::tag.create');
    }
    
    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $this->model->insertData($request->all(), 'tag');
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
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        $item = $this->model->findByLang($id, ['taxs.*', 'td.*'], $lang);
        return view('admin::tag.edit', compact('item', 'lang'));
    }

}
