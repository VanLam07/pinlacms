<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Tax;
use PlMenu;
use Breadcrumb;

class CatController extends BaseController {

    protected $model;
    protected $locale;
    protected $cap_accept = 'manage_cats';

    public function __construct(Tax $cat) {
        PlMenu::setActive('cats');
        parent::__construct();
        Breadcrumb::add(trans('admin::view.categories'), route('admin::cat.index'));
        $this->model = $cat;
        $this->locale = currentLocale();
    }

    public function index(Request $request) {
        $data = $request->all();
        $data['fields'] = ['taxs.id', 'taxs.image_id', 'taxs.parent_id', 'taxs.order', 'td.name', 'td.slug'];
        $items = $this->model->getData('cat', $data);
        $parent = $items->isEmpty() ? 0 : $items->first()->parent_id;
        $tableCats = $this->model->tableCats($items, $parent);
        return view('admin::cat.index', compact('items', 'tableCats'));
    }

    public function create() {
        Breadcrumb::add(trans('admin::view.create'), route('admin::cat.create'));
        
        $parents = $this->model->getData('cat', [
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
        $item = $this->model->findByLang($id, ['taxs.*', 'td.*'], $lang);
        $parents = $this->model->getData('cat', [
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name'],
            'exclude' => [$id],
            'per_page' => -1,
            'orderby' => 'td.name'
        ]);
        return view('admin::cat.edit', compact('lang', 'item', 'parents'));
    }

    public function destroy($id) {
        if (!$this->model->destroy($id)) {
            return redirect()->back()->with('error_mess', trans('admin::message.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }


}
