<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use Illuminate\Validation\ValidationException;
use App\Models\Tax;
use Exception;
use PlMenu;
use Breadcrumb;

class AlbumController extends BaseController
{
    protected $model;
    protected $cap_accept = 'manage_cats';

    public function __construct(Tax $album) {
        parent::__construct();
        PlMenu::setActive('albums');
        Breadcrumb::add(trans('admin::view.albums'), route('admin::album.index'));
        $this->model = $album;
    }

    public function index(Request $request) {
        canAccess($this->cap_accept);
        
        $data = $request->all();
        $albums = $this->model->getData('album', $data);
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
            $this->model->insertData($request->all(), 'album');
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }

    public function edit($id, Request $request) {
        canAccess($this->cap_accept);
        
        Breadcrumb::add(trans('admin::view.edit'));
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        $item = $this->model->findByLang($id, ['taxs.*', 'td.*'], $lang);
        return view('admin::album.edit', compact('item', 'lang'));
    }

    public function update($id, Request $request) {
        canAccess($this->cap_accept);
        
        try {
            $this->model->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }
    
}
