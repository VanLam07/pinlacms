<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\User;
use App\Models\Tax;
use App\Models\Media;

class MediaController extends BaseController
{
    protected $model;
    protected $user;

    public function __construct(Media $media, Tax $album, User $user) {
        $this->model = $media;
        $this->album = $album;
        $this->user = $user;
    }

    public function index(Request $request) {
        $items = $this->model->getData($request->all());
        return view('admin::media.index', ['items' => $items]);
    }

    public function create() {
//        canAccess('publish_posts');

        $albums = $this->album->getData('album', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name']]
        );
        $users = null;
//        if (cando('manage_posts')) {
            $users = $this->user->getData([
                'orderby' => 'name',
                'order' => 'asc',
                'per_page' => -1,
                'fields' => ['id', 'name']]
            );
//        }
        return view('admin::media.create', compact('albums', 'users'));
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
        $albums = $this->album->getData('album', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name']
        ]);
        $users = null;
//        if (cando('manage_posts')) {
            $users = $this->user->getData([
                'orderby' => 'name',
                'order' => 'asc',
                'per_page' => 20,
                'fields' => ['name', 'id']
            ])->pluck('name', 'id')->toArray();
//        }
        $item = $this->model->findByLang($id, ['medias.*', 'md.*'], $lang);
        $currAlbums = $item->albums->pluck('id')->toArray();
        return view('admin::media.edit', compact('item', 'albums', 'users', 'currAlbums', 'lang'));
    }

    public function update($id, Request $request) {
//        canAccess('edit_my_post', $this->model->get_author_id($id));
        return parent::update($id, $request);
    }

    public function multiActions(Request $request) {
        return parent::multiActions($request);
    }
}
