<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use PlMenu;

class SliderController extends BaseController
{
   protected $cap_accept = 'manage_cats';

    public function __construct() {
        PlMenu::setActive('sliders');
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $sliders = Tax::getData('slider', $data);
        return view('admin::slider.index', ['items' => $sliders]);
    }

    public function create() {
        canAccess($this->cap_accept);
        
        return view('admin::slider.create');
    }

    public function store(Request $request) {
        canAccess($this->cap_accept);
        
        DB::beginTransaction();
        try {
            Tax::insertData($request->all(), 'slider');
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
        
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        $item = Tax::findByLang($id, ['taxs.*', 'td.*'], $lang);
        return view('admin::slider.edit', compact('item', 'lang'));
    }

}
