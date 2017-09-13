<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\Menu;
use App\Models\Tax;
use App\Models\PostType;

class MenuController extends Controller {

    protected $menu;
    protected $tax;
    protected $post;

    public function __construct(Menu $menu, Tax $tax, PostType $post) {
        canAccess('manage_menus');

        $this->menu = $menu;
        $this->tax = $tax;
        $this->post = $post;
    }

    public function index(Request $request) {
        $data = $request->all();
        $menus = $this->menu->getData($data);
        return view('manage.menu.index', ['items' => $menus]);
    }

    public function create() {
        $parents = $this->menu->getData(['orderby' => 'pivot_title']);
        $groups = $this->tax->getData('menucat', ['orderby' => 'pivot_name', 'fields' => ['id']]);

        $cats = $this->tax->getData('cat', ['orderby' => 'pivot_name', 'fields' => ['id']]);
        $tags = $this->tax->getData('tag', ['orderby' => 'pivot_name', 'fields' => ['id']]);
        $posts = $this->post->getData('post', ['orderby' => 'pivot_title', 'fields' => ['id']]);
        $pages = $this->post->getData('page', ['orderby' => 'pivot_title', 'fields' => ['id']]);
        return view('manage.menu.create', compact('parents', 'groups', 'cats', 'tags', 'posts', 'pages'));
    }

    public function store(Request $request) {
        try {
            $this->menu->insertData($request->all());
            return redirect()->back()->with('succ_mess', trans('manage.store_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->validator);
        } catch (DbException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMess());
        }
    }

    public function edit($id) {
        $item = $this->menu->find($id);
        return view('manage.menu.edit', ['item' => $item]);
    }

    public function update($id, Request $request) {
        try {
            $this->menu->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('manage.update_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->validator);
        }
    }

    public function destroy($id) {
        if (!$this->menu->destroyData($id)) {
            return redirect()->back()->with('error_mess', trans('manage.no_item'));
        }
        return redirect()->back()->with('succ_mess', trans('manage.destroy_success'));
    }
    
    public function asynDestroy(Request $request){
        if(!$request->has('id')){
            return response()->json(trans('manage.no_item'), 422);
        }
        $id = $request->get('id');
        $this->menu->destroy($id);
        return response()->json(trans('manage.destroy_success'));
    }

    public function multiAction(Request $request) {
        try {
            $this->menu->actions($request);
            return redirect()->back()->withInput()->with('succ_mess', trans('message.action_success'));
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }

    public function getType(Request $request) {
        if (!$request->has('menu_id')) {
            return response()->json(trans('manage.no_item'), 422);
        }
        $lang = current_locale();
        if($request->has('lang')){
            $lang = $request->get('lang');
        }
        
        $menu_id = $request->get('menu_id');

        $menu = $this->menu->find($menu_id);
        if (!$menu) {
            return response()->json(trans('manage.no_item'), 422);
        }

        $result = null;
        switch ($menu->menu_type) {
            case 0:
                $result = $this->menu->findCustom($menu_id, ['md.*'], $lang);
                break;
            case 1:
                $result = $this->post->findByLang($menu->type_id, ['posts.id', 'pd.title'], $lang);
                break;
            case 2:
                $result = $this->post->findByLang($menu->type_id, ['posts.id', 'pd.title'], $lang);
                break;
            case 3:
                $result = $this->tax->findByLang($menu->type_id, ['taxs.id', 'td.name'], $lang);
                break;
            case 4:
                $result = $this->tax->findByLang($menu->type_id, ['taxs.id', 'td.name'], $lang);
                break;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }
        return $result;
    }

}
