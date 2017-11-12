<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\PostType;
use Illuminate\Validation\ValidationException;
use File;
use DB;
use Exception;
use PlMenu;
use Breadcrumb;

class PageController extends BaseController
{
    protected $model;
    protected $templates = [];

    public function __construct(PostType $page) {
        parent::__construct();
        $this->model = $page;
        $this->templates = ['' => trans('admin::view.selection')];
        
        $view_path = config('view.paths')[0].'\front\templates';
        $files = File::files($view_path);
        foreach ($files as $file){
            $name = explode('.', basename($file))[0];
            $this->templates[$name] = $name;
        }
        PlMenu::setActive('pages');
        Breadcrumb::add(trans('admin::view.pages'), route('admin::page.index'));
    }

    public function index(Request $request) {
        canAccess('view_post');
        
        $items = $this->model->getData('page', $request->all());
        return view('admin::page.index', ['items' => $items]);
    }

    public function create() {
        canAccess('publish_post');

        Breadcrumb::add(trans('admin::view.create'));
        return view('admin::page.create', ['templates' => $this->templates]);
    }

    public function store(Request $request) {
        canAccess('publish_post');
        
        DB::beginTransaction();
        try {
            $this->model->insertData($request->all(), 'page');
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (ValidationException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (Exception $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }

    public function edit($id, Request $request) {
        canAccess('edit_post', $this->model->getAuthorId($id));

        Breadcrumb::add(trans('admin::view.edit'));
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        $item = $this->model->findByLang($id, ['posts.*', 'pd.*'], $lang);
        $templates = $this->templates;
        return view('admin::page.edit', compact('item', 'templates', 'lang'));
    }

    public function update($id, Request $request) {
        canAccess('edit_post', $this->model->getAuthorId($id));
        
        return parent::update($id, $request);
    }


    public function multiAction(Request $request) {
        
        return parent::multiActions($request);
    }
}
