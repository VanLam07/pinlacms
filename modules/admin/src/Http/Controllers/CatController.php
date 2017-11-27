<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Tax;
use PlMenu;
use Breadcrumb;

class CatController extends BaseController {

    protected $locale;
    protected $cap_accept = 'manage_cats';

    public function __construct() {
        PlMenu::setActive('cats');
        parent::__construct();
        Breadcrumb::add(trans('admin::view.categories'), route('admin::cat.index'));
        $this->locale = currentLocale();
    }

    public function index(Request $request) {
        $data = $request->all();
        $data['fields'] = ['taxs.id', 'taxs.image_id', 'taxs.parent_id', 'taxs.order', 'td.name', 'td.slug'];
        $items = Tax::getData('cat', $data);
        $parent = $items->isEmpty() ? 0 : $items->first()->parent_id;
        $tableCats = Tax::tableCats($items, $parent);
        return view('admin::cat.index', compact('items', 'tableCats'));
    }

    public function create() {
        Breadcrumb::add(trans('admin::view.create'), route('admin::cat.create'));
        
        $parents = Tax::getData('cat', [
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name'],
            'per_page' => -1,
            'orderby' => 'td.name'
        ]);
        return view('admin::cat.create', ['parents' => $parents, 'lang' => $this->locale]);
    }

    public function edit($id, Request $request) {
        Breadcrumb::add(trans('admin::view.edit'));
        
        $lang = $request->get('lang');
        if (!$lang) {
            $lang = currentLocale();
        }
        $item = Tax::findByLang($id, ['taxs.*', 'td.*'], $lang);
        $parents = Tax::getData('cat', [
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name'],
            'exclude' => [$id],
            'per_page' => -1,
            'orderby' => 'td.name'
        ]);
        return view('admin::cat.edit', compact('lang', 'item', 'parents'));
    }

    public function destroy($id) {
        if (!Tax::destroy($id)) {
            return redirect()->back()->with('error_mess', trans('admin::message.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }


}
