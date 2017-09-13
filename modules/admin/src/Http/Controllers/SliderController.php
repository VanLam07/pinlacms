<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\Tax;

class SliderController extends Controller
{
   protected $slider;

    public function __construct(Tax $slider) {
        canAccess('manage_cats');

        $this->slider = $slider;
    }

    public function index(Request $request) {
        $data = $request->all();
        $sliders = $this->slider->getData('slider', $data);
        return view('manage.slider.index', ['items' => $sliders]);
    }

    public function create() {
        return view('manage.slider.create');
    }

    public function store(Request $request) {
        try {
            $this->slider->insertData($request->all(), 'slider');
            return redirect()->back()->with('succ_mess', trans('manage.store_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->validator);
        } catch (DbException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMess());
        }
    }

    public function edit($id, Request $request) {
        $lang = current_locale();
        if($request->has('lang')){
            $lang = $request->get('lang');
        }
        $item = $this->slider->findByLang($id, ['taxs.*', 'td.*'], $lang);
        return view('manage.slider.edit', compact('item', 'lang'));
    }

    public function update($id, Request $request) {
        try {
            $this->slider->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('manage.update_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->validator);
        }
    }

    public function destroy($id) {
        if (!$this->slider->destroy($id)) {
            return redirect()->back()->with('error_mess', trans('manage.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('manage.destroy_success'));
    }

    public function multiAction(Request $request) {
        try {
            $this->slider->actions($request);
            return redirect()->back()->withInput()->with('succ_mess', trans('message.action_success'));
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
}
