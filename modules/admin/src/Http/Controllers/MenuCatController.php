<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Menu;
use App\Models\Tax;
use App\Exceptions\PlException;
use Illuminate\Support\Facades\DB;
use PlMenu;
use Breadcrumb;

class MenuCatController extends BaseController {

    protected $cap_accept = 'manage_menus';
    protected $model;

    public function __construct() {
        parent::__construct();
        PlMenu::setActive('group-menus');
        Breadcrumb::add(trans('admin::view.menucats'), route('admin::menucat.index'));
        $this->model = Tax::class;
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $menucats = Tax::getData('menucat', $data);
        return view('admin::menucat.index', ['items' => $menucats]);
    }

    public function create() {
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'));
        return view('admin::menucat.create');
    }

    public function store(Request $request) {
        canAccess($this->cap_accept);
        
        try {
            $item = Tax::insertData($request->all(), 'menucat');
            return redirect()->route('admin::menucat.edit', $item->id)
                    ->with('succ_mess', trans('admin::message.store_success'));
        } catch (PlException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }
    
    public function storeItems(Request $request){
        canAccess($this->cap_accept);
        
        $menu_items = $request->get('menuItems');
        if($menu_items){
            foreach ($menu_items as $item){ 
                $item = (array) $item;
                $item['group_id'] = $request->get('group_id');
                $item['type_id'] = isset($item['id']) ? $item['id'] : 0;
                $item['lang'] = $request->has('lang') ? $request->get('lang') : currentLocale();
                Menu::insertData($item);
            }
        }
        if($request->wantsJson() || $request->ajax()){
            return response()->json(['success' => true]);
        }
        return true;
    }

    public function edit($id, Request $request) {
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $lang = $request->get('lang');
        if (!$lang) {
            $lang = currentLocale();
        }
        $item = Tax::findByLang($id, ['taxs.id', 'td.slug', 'td.name'], $lang);
        return view('admin::menucat.edit', compact('item', 'lang'));
    }

    public function update($id, Request $request) {
        canAccess($this->cap_accept);
        
        DB::beginTransaction();
        try {
            Tax::updateData($id, $request->all());
            
            $menus = $request->get('menus');
            if($menus){
                foreach ($menus as $menu_id => $menu){
                    $menu = (array) $menu;
                    $menu['lang'] = $request->get('lang');
                    Menu::updateData($menu_id, $menu);
                }
            }
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (PlException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }
    
    public function updateOrderItems(Request $request){
        canAccess($this->cap_accept);
        
        parse_str($request->get('data'), $menuData);
        if (!isset($menuData['id'])) {
            return response()->json(trans('admin::message.invalid_data'));
        }
        //update menu
        Tax::updateData($menuData['id'], $menuData);
        foreach ($menuData['menus'] as $menuId => $menu){
            $menu = (array) $menu;
            $menu['lang'] = $menuData['lang'];
            Menu::updateData($menuId, $menu);
        }
        
        //update sort order
        $menus = $request->get('menus');
        if($menus){
            $this->nestedOrderUpdate($menus);
        }
        return response()->json(trans('admin::message.update_success'));
    }
    
    public function nestedOrderUpdate($items, $parent=null){
        canAccess($this->cap_accept);
        
        foreach ($items as $key => $item){
            Menu::updateOrder($item['id'], $key, $parent);
            if(count($item['childs']) > 0){
                $this->nestedOrderUpdate($item['childs'], $item['id']);
            }
        }
    }
    
    public function destroy($id) {
        canAccess($this->cap_accept);
        
        if (!Tax::destroyData($id)) {
            return redirect()->back()->with('error_mess', trans('admin::message.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }

    public function getNestedMenus(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $data['with_target'] = true;
        $tblPrefix = DB::getTablePrefix();
        $data['fields'] = ['menus.*', 'md.*', DB::raw('IFNULL('.$tblPrefix.'tax_desc.name, '.$tblPrefix.'post_desc.title) as target_title')];
        $menus = Menu::getData($data);
        $nested = Tax::toNested($menus);
        return $nested;
    }

}
