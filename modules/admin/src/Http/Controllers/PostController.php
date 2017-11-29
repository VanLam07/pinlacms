<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\PostType;
use App\Models\Tax;
use App\User;
use Admin\Facades\AdConst;
use PlMenu;
use Breadcrumb;

class PostController extends BaseController {

    protected $model;
    protected $cap_create = 'publish_post';
    protected $cap_edit = 'edit_post';
    protected $cap_remove = 'remove_post';

    public function __construct() {
        parent::__construct();
        Breadcrumb::add(trans('admin::view.posts'), route('admin::post.index'));
        $this->model = PostType::class;
    }

    public function index(Request $request) {
        canAccess('view_post');
        
        PlMenu::setActive(['posts', 'post_all']);
        $items = PostType::getData('post', $request->all());
        return view('admin::post.index', ['items' => $items]);
    }

    public function create() {
        canAccess($this->cap_create);
        
        Breadcrumb::add(trans('admin::view.create'), route('admin::post.create'));
        PlMenu::setActive(['posts', 'post_create']);
        $cats = Tax::getData('cat', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name']]
        );
        $tags = Tax::getData('tag', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'td.name']]
        );
        
        $users = null;
        if (canDo('edit_post', null, AdConst::CAP_OTHER)) {
            $users = User::getData([
                'orderby' => 'name',
                'order' => 'asc',
                'pre_page' => -1,
                'fields' => ['id', 'name']]
            );
        }
        return view('admin::post.create', compact('cats', 'tags', 'users'));
    }

    public function store(Request $request) {
        canAccess($this->cap_create);

        try {
            return parent::store($request);
        } catch (\App\Exceptions\PlException $ex) {
            return redirect()->back()->withInput()
                    ->with('error_mess', $ex->getError());
        }
    }

    public function edit($id, Request $request) {
        $lang = $request->get('lang');
        if (!$lang) {
            $lang = currentLocale();
        }
        $item = PostType::findByLang($id, ['posts.*', 'pd.*'], $lang);
        canAccess($this->cap_edit, $item->author_id);

        Breadcrumb::add(trans('admin::view.edit'));
        PlMenu::setActive(['posts', 'post_edit']);
        
        $cats = Tax::getData('cat', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name']
        ]);
        $tags = Tax::getData('tag', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'td.name']
        ]);
        $users = null;
        if (canDo('edit_post', null, AdConst::CAP_OTHER)) {
            $users = User::getData([
                'orderby' => 'name',
                'order' => 'asc',
                'fields' => ['name', 'id']
            ])->pluck('name', 'id')->toArray();
        }
        
        $curr_cats = $item->cats->pluck('id')->toArray();
        $curr_tags = $item->tags->pluck('id')->toArray();
        return view('admin::post.edit', compact('item', 'cats', 'tags', 'users', 'curr_cats', 'curr_tags', 'lang'));
    }

    public function update($id, Request $request) {
        canAccess('edit_post', PostType::getAuthorId($id));
        return parent::update($id, $request);
    }

    public function multiAction(Request $request) {
        canAccess('remove_post', null, AdConst::CAP_OTHER);
        
        return parent::multiActions($id, $request);
    }

}
