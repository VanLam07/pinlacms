<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\PostType;
use App\Models\Tax;
use App\User;
use Illuminate\Validation\ValidationException;

class PostController extends BaseController {

    protected $post;
    protected $tax;
    protected $user;

    public function __construct(PostType $post, Tax $tax, User $user) {
        $this->model = $post;
        $this->tax = $tax;
        $this->user = $user;
    }

    public function index(Request $request) {
        $items = $this->model->getData('post', $request->all());
        return view('admin::post.index', ['items' => $items]);
    }

    public function create() {
//        canAccess('publish_posts');

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
//        if (cando('manage_posts')) {
            $users = $this->user->getData([
                'orderby' => 'name',
                'order' => 'asc',
                'pre_page' => -1,
                'fields' => ['id', 'name']]
            );
//        }
        return view('admin::post.create', compact('cats', 'tags', 'users'));
    }

    public function store(Request $request) {
//        canAccess('publish_posts');

        return parent::store($request);
    }

    public function edit($id, Request $request) {
//        canAccess('edit_my_post', $this->model->get_author_id($id));

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
//        if (cando('manage_posts')) {
            $users = $this->user->getData([
                'orderby' => 'name',
                'order' => 'asc',
                'fields' => ['name', 'id']
            ])->pluck('name', 'id')->toArray();
//        }
        $item = $this->model->findByLang($id, ['posts.*', 'pd.*'], $lang);
        $curr_cats = $item->cats->pluck('id')->toArray();
        $curr_tags = $item->tags->pluck('id')->toArray();
        return view('admin::post.edit', compact('item', 'cats', 'tags', 'users', 'curr_cats', 'curr_tags', 'lang'));
    }

    public function update($id, Request $request) {
//        canAccess('edit_my_post', $this->model->get_author_id($id));
        return parent::update($id, $request);
    }

//    public function multiAction(Request $request) {
//        if(!cando('remove_other_posts')){
//            return redirect()->back()->withInput()->with('error_mess', trans('auth.authorize'));
//        }
//        try {
//            $this->model->actions($request);
//            return redirect()->back()->withInput()->with('succ_mess', trans('message.action_success'));
//        } catch (\Exception $ex) {
//            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
//        }
//    }

}
