<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\PostType;
use App\Models\Tax;
use App\User;
use Admin\Facades\AdConst;
use PlMenu;

class PostController extends BaseController {

    protected $post;
    protected $tax;
    protected $user;
    
    protected $cap_create = 'publish_post';
    protected $cap_edit = 'edit_post';
    protected $cap_remove = 'remove_post';

    public function __construct(PostType $post, Tax $tax, User $user) {
        $this->model = $post;
        $this->tax = $tax;
        $this->user = $user;
    }

    public function index(Request $request) {
        canAccess('view_post');
        
        PlMenu::setActive(['posts', 'post_all']);
        $items = $this->model->getData('post', $request->all());
        return view('admin::post.index', ['items' => $items]);
    }

    public function create() {
        canAccess($this->cap_create);
        
        PlMenu::setActive(['posts', 'post_create']);
        $cats = $this->tax->getData('cat', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name']]
        );
        $tags = $this->tax->getData('tag', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'td.name']]
        );
        
        $users = null;
        if (canDo('edit_post', null, AdConst::CAP_OTHER)) {
            $users = $this->user->getData([
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

        return parent::store($request);
    }

    public function edit($id, Request $request) {
        canAccess($this->cap_edit, $this->model->getAuthorId($id));

        PlMenu::setActive(['posts', 'post_edit']);
        $lang = $request->get('lang');
        if (!$lang) {
            $lang = currentLocale();
        }
        $cats = $this->tax->getData('cat', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name']
        ]);
        $tags = $this->tax->getData('tag', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'td.name']
        ]);
        $users = null;
        if (canDo('edit_post', null, AdConst::CAP_OTHER)) {
            $users = $this->user->getData([
                'orderby' => 'name',
                'order' => 'asc',
                'fields' => ['name', 'id']
            ])->pluck('name', 'id')->toArray();
        }
        
        $item = $this->model->findByLang($id, ['posts.*', 'pd.*'], $lang);
        $curr_cats = $item->cats->pluck('id')->toArray();
        $curr_tags = $item->tags->pluck('id')->toArray();
        return view('admin::post.edit', compact('item', 'cats', 'tags', 'users', 'curr_cats', 'curr_tags', 'lang'));
    }

    public function update($id, Request $request) {
        canAccess('edit_post', $this->model->getAuthorId($id));
        return parent::update($id, $request);
    }

    public function multiAction(Request $request) {
        canAccess('remove_post', null, \Admin\Facades\AdConst::CAP_OTHER);
        
        return parent::update($id, $request);
    }

}
