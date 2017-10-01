<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use Illuminate\Validation\ValidationException;
use App\Models\Tax;
use Exception;

class AlbumController extends BaseController
{
    protected $model;

    public function __construct(Tax $album) {
//        canAccess('manage_cats');

        $this->model = $album;
    }

    public function index(Request $request) {
        $data = $request->all();
        $albums = $this->model->getData('album', $data);
        return view('admin::album.index', ['items' => $albums]);
    }

    public function create() {
        return view('admin::album.create');
    }

    public function store(Request $request) {
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
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        $item = $this->model->findByLang($id, ['taxs.*', 'td.*'], $lang);
        return view('admin::album.edit', compact('item', 'lang'));
    }

    public function update($id, Request $request) {
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
