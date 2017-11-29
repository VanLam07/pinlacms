<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\PostType;
use App\Exceptions\PlException;
use File;
use DB;
use Exception;
use PlMenu;
use Breadcrumb;

class PageController extends BaseController
{
    protected $templates = [];
    protected $model;

    public function __construct() {
        parent::__construct();
        $this->templates = ['' => trans('admin::view.selection')];
        $this->model = PostType::class;
        
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
        
        $items = PostType::getData('page', $request->all());
        return view('admin::page.index', ['items' => $items]);
    }

    public function create() {
        canAccess('publish_post');

        Breadcrumb::add(trans('admin::view.create'));
        PlMenu::setActive('page_create');
        
        return view('admin::page.create', ['templates' => $this->templates]);
    }

    public function store(Request $request) {
        canAccess('publish_post');
        
        DB::beginTransaction();
        try {
            PostType::insertData($request->all(), 'page');
            DB::commit();
            return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
        } catch (PlException $ex) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_mess', $ex->getError());
        }
    }

    public function edit($id, Request $request) {
        canAccess('edit_post', PostType::getAuthorId($id));

        Breadcrumb::add(trans('admin::view.edit'));
        $lang = $request->get('lang');
        if(!$lang){
            $lang = currentLocale();
        }
        $item = PostType::findByLang($id, ['posts.*', 'pd.*'], $lang);
        $templates = $this->templates;
        return view('admin::page.edit', compact('item', 'templates', 'lang'));
    }

    public function update($id, Request $request) {
        canAccess('edit_post', PostType::getAuthorId($id));
        
        return parent::update($id, $request);
    }


    public function multiAction(Request $request) {
        
        return parent::multiActions($request);
    }
}
