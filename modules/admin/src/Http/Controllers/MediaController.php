<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\User;
use App\Models\Tax;
use App\Models\Media;
use Admin\Facades\AdConst;
use PlMenu;
use Breadcrumb;

class MediaController extends BaseController
{
    
    protected $cap_create = 'publish_post';
    protected $cap_edit = 'edit_post';
    protected $cap_remove = 'remove_post';

    public function __construct() {
        parent::__construct();
        PlMenu::setActive('medias');
        Breadcrumb::add(trans('admin::view.medias'), route('admin::media.index'));
    }

    public function index(Request $request) {
        canAccess('view_post');
        
        $items = Media::getData($request->all());
        return view('admin::media.index', ['items' => $items]);
    }

    public function create() {
        canAccess($this->cap_create);

        Breadcrumb::add(trans('admin::view.create'));
        $albums = Tax::getData('album', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name']]
        );
        
        $users = null;
        if (canDo('edit_post', null, AdConst::CAP_OTHER)) {
            $users = User::getData([
                'orderby' => 'name',
                'order' => 'asc',
                'per_page' => -1,
                'fields' => ['id', 'name']]
            );
        }
        return view('admin::media.create', compact('albums', 'users'));
    }

    public function store(Request $request) {
        canAccess($this->cap_create);
        
        return parent::store($request);
    }

    public function edit($id, Request $request) {
        canAccess('edit_post', Media::getAuthorId($id));

        Breadcrumb::add(trans('admin::view.edit'));
        $lang = $request->get('lang');
        if (!$lang) {
            $lang = currentLocale();
        }
        $albums = Tax::getData('album', [
            'orderby' => 'name',
            'order' => 'asc',
            'per_page' => -1,
            'fields' => ['taxs.id', 'taxs.parent_id', 'td.name']
        ]);
        $users = null;
        if (cando('edit_post', null, \Admin\Facades\AdConst::CAP_OTHER)) {
            $users = User::getData([
                'orderby' => 'name',
                'order' => 'asc',
                'per_page' => -1,
                'fields' => ['name', 'id']
            ])->pluck('name', 'id')->toArray();
        }
        $item = Media::findByLang($id, ['medias.*', 'md.*'], $lang);
        $currAlbums = $item->albums->pluck('id')->toArray();
        return view('admin::media.edit', compact('item', 'albums', 'users', 'currAlbums', 'lang'));
    }

    public function update($id, Request $request) {
        canAccess($this->cap_edit, Media::getAuthorId($id));
        
        return parent::update($id, $request);
    }
}
