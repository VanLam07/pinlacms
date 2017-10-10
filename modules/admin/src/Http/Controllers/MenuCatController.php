<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Menu;
use App\Models\Tax;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use PlMenu;

class MenuCatController extends BaseController {

    protected $model;
    protected $menu;

    public function __construct(Tax $tax, Menu $menu) {
        canAccess('manage_menus');
        PlMenu::setActive('group-menus');
        
        $this->model = $tax;
        $this->menu = $menu;
    }

    public function index(Request $request) {
        $data = $request->all();
        $menucats = $this->model->getData('menucat', $data);
        return view('admin::menucat.index', ['items' => $menucats]);
    }

    public function create() {
        return view('admin::menucat.create');
    }

    public function store(Request $request) {
        try {
            $this->model->insertData($request->all(), 'menucat');
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
    
    public function storeItems(Request $request){
        $menu_items = $request->get('menuItems');
        if($menu_items){
            foreach ($menu_items as $item){ 
                $item = (array) $item;
                $item['group_id'] = $request->get('group_id');
                $item['type_id'] = isset($item['id']) ? $item['id'] : 0;
                $item['lang'] = $request->has('lang') ? $request->get('lang') : currentLocale();
                $this->menu->insertData($item);
            }
        }
        if($request->wantsJson() || $request->ajax()){
            return response()->json(['success' => true]);
        }
        return true;
    }

    public function edit($id, Request $request) {
        $lang = $request->get('lang');
        if (!$lang) {
            $lang = currentLocale();
        }
        $item = $this->model->findByLang($id, ['taxs.id', 'td.slug', 'td.name'], $lang);
        return view('admin::menucat.edit', compact('item', 'lang'));
    }

    public function update($id, Request $request) {     
        DB::beginTransaction();
        try {
            $this->model->updateData($id, $request->all());
            
            $menus = $request->get('menus');
            if($menus){
                foreach ($menus as $menu_id => $menu){
                    $menu = (array) $menu;
                    $menu['lang'] = $request->get('lang');
                    $this->menu->updateData($menu_id, $menu);
                }
            }
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (ValidationException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
    
    public function updateOrderItems(Request $request){
        $menus = $request->get('menus');
        if($menus){
            $this->nestedOrderUpdate($menus);
        }
        return response()->json(trans('admin::message.update_success'));
    }
    
    public function nestedOrderUpdate($items, $parent=null){
        foreach ($items as $key => $item){
            $this->menu->updateOrder($item['id'], $key, $parent);
            if(count($item['childs']) > 0){
                $this->nestedOrderUpdate($item['childs'], $item['id']);
            }
        }
    }
    
    public function destroy($id) {
        if (!$this->model->destroyData($id)) {
            return redirect()->back()->with('error_mess', trans('admin::message.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.destroy_success'));
    }

    public function getNestedMenus(Request $request) {
        $menus = $this->menu->getData($request->all());
        $nested = $this->model->toNested($menus);
        return $nested;
    }

}
