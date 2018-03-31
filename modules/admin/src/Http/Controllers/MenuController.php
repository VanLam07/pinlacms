<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Exceptions\PlException;
use App\Models\Menu;
use App\Models\Tax;
use App\Models\PostType;
use Admin\Facades\AdConst;

class MenuController extends BaseController {

    protected $cap_accept = 'manage_menus';
    protected $model;
    
    public function __construct() {
        parent::__construct();
        $this->model = Menu::class;
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $menus = Menu::getData($data);
        return view('manage.menu.index', ['items' => $menus]);
    }

    public function create() {
        canAccess($this->cap_accept);
        
        $parents = Menu::getData(['orderby' => 'pivot_title']);
        $groups = Tax::getData('menucat', ['orderby' => 'pivot_name', 'fields' => ['id']]);

        $cats = Tax::getData('cat', ['orderby' => 'pivot_name', 'fields' => ['id']]);
        $tags = Tax::getData('tag', ['orderby' => 'pivot_name', 'fields' => ['id']]);
        $posts = PostType::getData('post', ['orderby' => 'pivot_title', 'fields' => ['id']]);
        $pages = PostType::getData('page', ['orderby' => 'pivot_title', 'fields' => ['id']]);
        return view('manage.menu.create', compact('parents', 'groups', 'cats', 'tags', 'posts', 'pages'));
    }

    public function store(Request $request) {
        canAccess($this->cap_accept);
        
        try {
            Menu::insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (PlException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }

    public function edit($id) {
        canAccess($this->cap_accept);
        
        $item = Menu::find($id);
        return view('manage.menu.edit', ['item' => $item]);
    }

    public function update($id, Request $request) {
        canAccess($this->cap_accept);
        
        try {
            Menu::updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (PlException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }

    public function destroy($id) {
        canAccess($this->cap_accept);
        
        if (!Menu::destroyData($id)) {
            return redirect()->back()->with('error_mess', trans('admin::message.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }
    
    public function asynDestroy(Request $request){
        canAccess($this->cap_accept);
        
        if(!$request->has('id')){
            return response()->json(trans('admin::message.no_item'), 422);
        }
        $id = $request->get('id');
        Menu::destroy($id);
        return response()->json(trans('admin::message.destroy_success'));
    }

    public function multiAction(Request $request) {
        try {
            Menu::actions($request);
            return redirect()->back()->withInput()->with('succ_mess', trans('message.action_success'));
        } catch (PlException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }

    public function getType(Request $request) {
        canAccess($this->cap_accept);
        
        if (!$request->has('menu_id')) {
            return response()->json(trans('admin::message.no_item'), 422);
        }
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        
        $menu_id = $request->get('menu_id');

        $menu = Menu::find($menu_id);
        if (!$menu) {
            return response()->json(trans('admin::message.no_item'), 422);
        }

        $result = null;
        switch ($menu->model_type) {
            case AdConst::MENU_TYPE_CUSTOM:
                $result = Menu::findCustom($menu_id, ['md.*'], $lang);
                break;
            case AdConst::MENU_TYPE_PAGE:
            case AdConst::MENU_TYPE_POST:
                $result = PostType::findByLang($menu->type_id, ['posts.id', 'pd.title', 'posts.post_type'], $lang);
                break;
            case AdConst::MENU_TYPE_CAT:
            case AdConst::MENU_TYPE_TAX:
                $result = Tax::findByLang($menu->type_id, ['taxs.id', 'td.name', 'taxs.type'], $lang);
                break;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }
        return $result;
    }

    public function getListType(Request $request)
    {
        canAccess($this->cap_accept);
        if (!$request->has('menu_ids')) {
            return response()->json(trans('admin::message.no_item'), 422);
        }
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        
        $menuIds = $request->get('menu_ids');
        $menuTargets = Menu::whereIn('id', $menuIds)->get();
        if ($menuTargets->isEmpty()) {
            return response()->json(trans('admin::message.no_item'), 422);
        }
        $results = [];
        foreach ($menuTargets as $menu) {
            $target = null;
            switch ($menu->model_type) {
                case AdConst::MENU_TYPE_CUSTOM:
                    $target = Menu::findCustom($menu->id, ['md.*'], $lang);
                    break;
                case AdConst::MENU_TYPE_PAGE:
                case AdConst::MENU_TYPE_POST:
                    $target = PostType::findByLang($menu->type_id, ['posts.id', 'pd.title', 'posts.post_type'], $lang);
                    break;
                case AdConst::MENU_TYPE_CAT:
                case AdConst::MENU_TYPE_TAX:
                    $target = Tax::findByLang($menu->type_id, ['taxs.id', 'td.name', 'taxs.type'], $lang);
                    break;
            }
            $results[$menu->id] = $target;
        }
        return $results;
    }

}
