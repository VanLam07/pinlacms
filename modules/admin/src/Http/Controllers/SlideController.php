<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\Media;
use App\Models\Tax;

class SlideController extends Controller
{
    protected $slide;
    protected $slider;

    public function __construct(Media $slide, Tax $slider) {
        canAccess('manage_cats');
        
        $this->slide = $slide;
        $this->slider = $slider;
    }

    public function index($slider_id, Request $request) {
        $data = $request->all();
        $data['slider_id'] = $slider_id;
        $slider = $this->slider->findByLang($slider_id);
        $items = $this->slide->getData($data);
        return view('manage.slide.index', compact('items', 'slider_id', 'slider'));
    }

    public function create($slider_id) {
        return view('manage.slide.create', compact('slider_id'));
    }

    public function store(Request $request) {
        
        try {
            $this->slide->insertData($request->all(), 'slide'); 
            return redirect()->back()->with('succ_mess', trans('manage.store_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->validator);
        } catch (DbException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }

    public function edit($id, Request $request) {
        $lang = current_locale();
        if ($request->has('lang')) {
            $lang = $request->get('lang');
        }
        $item = $this->slide->findByLang($id, ['medias.*', 'md.*'], $lang);
        return view('manage.slide.edit', compact('item', 'lang'));
    }

    public function update($id, Request $request) {
        try {
            $this->slide->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('manage.update_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->validator);
        }
    }

    public function destroy($id) {
        if (!$this->slide->destroy($id)) {
            return redirect()->back()->with('error_mess', trans('manage.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('manage.destroy_success'));
    }

    public function multiAction(Request $request) {
        try {
            $this->slide->actions($request);
            return redirect()->back()->withInput()->with('succ_mess', trans('message.action_success'));
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
}
