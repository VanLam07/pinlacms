<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Exceptions\PlException;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Media;
use App\Models\Tax;
use PlMenu;

class SlideController extends BaseController
{
    protected $cap_accept = 'manage_cats';
    protected $model;

    public function __construct() {
        PlMenu::setActive('sliders');
        $this->model = Media::class;
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $slider_id = $request->get('slider_id');
        if (!$slider_id) {
            abort(404);
        }
        $data = $request->all();
        $slider = Tax::findByLang($slider_id);
        $items = Media::getData($data);
        return view('admin::slide.index', compact('items', 'slider_id', 'slider'));
    }

    public function create(Request $request) {
        canAccess($this->cap_accept);
        
        $slider_id = $request->get('slider_id');
        if (!$slider_id) {
            abort(404);
        }
        $slider = Tax::findByLang($slider_id);
        return view('admin::slide.create', compact('slider_id', 'slider'));
    }

    public function store(Request $request) {
        canAccess($this->cap_accept);
        
        DB::beginTransaction();
        try {
            Media::insertData($request->all(), 'slide');
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (PlException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }

    public function edit($id, Request $request) {
        canAccess($this->cap_accept);
        
        $lang = $request->get('lang');
        if (!$lang) {
            $lang = currentLocale();
        }
        $item = Media::findByLang($id, ['medias.*', 'md.*'], $lang);
        $slider = Tax::findByLang($item->slider_id);
        return view('admin::slide.edit', compact('item', 'lang', 'slider'));
    }

}
