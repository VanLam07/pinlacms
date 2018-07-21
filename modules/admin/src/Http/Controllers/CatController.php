<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Tax;
use PlMenu;
use Breadcrumb;
use Admin\Facades\AdConst;

class CatController extends BaseController {

    protected $locale;
    protected $cap_accept = 'manage_cats';
    protected $model;

    public function __construct() {
        PlMenu::setActive('cats');
        parent::__construct();
        Breadcrumb::add(trans('admin::view.categories'), route('admin::cat.index', ['status' => AdConst::STT_PUBLISH]));
        $this->locale = currentLocale();
        $this->model = Tax::class;
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $data['fields'] = ['taxs.id', 'taxs.image_id', 'taxs.parent_id', 'taxs.order', 'td.name', 'td.slug'];
        $items = Tax::getData('cat', $data);
        $tableCats = Tax::tableCats($items);
        return view('admin::cat.index', compact('items', 'tableCats'));
    }

    public function create() {
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'), route('admin::cat.create'));
        
        $parents = Tax::getData('cat', [
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name'],
            'per_page' => -1,
            'orderby' => 'td.name'
        ]);
        return view('admin::cat.create', ['parents' => $parents, 'lang' => $this->locale]);
    }
    
    public function redirectEdit($item)
    {
        return redirect()->route('admin::cat.edit', $item->id)
                ->with('succ_mess', trans('admin::message.store_success'));
    }

    public function edit($id, Request $request) {
        canAccess($this->cap_accept);
        
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
        canAccess($this->cap_accept);
        
        if (!Tax::destroy($id)) {
            return redirect()->back()->with('error_mess', trans('admin::message.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }


}
