<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Exceptions\PlException;
use App\Models\Tax;
use PlMenu;
use Breadcrumb;

class AlbumController extends BaseController
{
    protected $cap_accept = 'manage_cats';
    protected $model;

    public function __construct() {
        parent::__construct();
        PlMenu::setActive('albums');
        Breadcrumb::add(trans('admin::view.albums'), route('admin::album.index'));
        $this->model = Tax::class;
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $albums = Tax::getData('album', $data);
        return view('admin::album.index', ['items' => $albums]);
    }

    public function create() {
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.create'), route('admin::album.create'));
        return view('admin::album.create');
    }

    public function store(Request $request) {
        canAccess($this->cap_accept);
        
        try {
            $item = Tax::insertData($request->all(), 'album');
            return redirect()->route('admin::album.edit', $item->id)
                    ->with('succ_mess', trans('admin::message.store_success'));
        } catch (PlException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }

    public function edit($id, Request $request) {
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        $item = Tax::findByLang($id, ['taxs.*', 'td.*'], $lang);
        $medias = $item->medias;
        return view('admin::album.edit', compact('item', 'lang', 'medias'));
    }

    public function update($id, Request $request) {
        canAccess($this->cap_accept);
        
        try {
            Tax::updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (PlException $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }
    
}
