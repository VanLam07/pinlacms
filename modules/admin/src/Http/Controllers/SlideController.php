<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Media;
use App\Models\Tax;
use PlMenu;

class SlideController extends BaseController
{
    protected $model;
    protected $slider;

    public function __construct(Media $slide, Tax $slider) {
        canAccess('manage_cats');
        PlMenu::setActive('sliders');
        
        $this->model = $slide;
        $this->slider = $slider;
    }

    public function index(Request $request) {
        $slider_id = $request->get('slider_id');
        if (!$slider_id) {
            abort(404);
        }
        $data = $request->all();
        $slider = $this->slider->findByLang($slider_id);
        $items = $this->model->getData($data);
        return view('admin::slide.index', compact('items', 'slider_id', 'slider'));
    }

    public function create(Request $request) {
        $slider_id = $request->get('slider_id');
        if (!$slider_id) {
            abort(404);
        }
        $slider = $this->slider->findByLang($slider_id);
        return view('admin::slide.create', compact('slider_id', 'slider'));
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {
            $this->model->insertData($request->all(), 'slide');
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
        if (!$lang) {
            $lang = currentLocale();
        }
        $item = $this->model->findByLang($id, ['medias.*', 'md.*'], $lang);
        $slider = $this->slider->findByLang($item->slider_id);
        return view('admin::slide.edit', compact('item', 'lang', 'slider'));
    }

}
