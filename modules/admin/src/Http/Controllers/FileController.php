<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\File as FileModel;
use App\Exceptions\PlException;
use App\User;
use PlMenu;
use Breadcrumb;

class FileController extends BaseController {

    protected $cap_create = 'publish_file';
    protected $cap_edit = 'edit_file';
    protected $cap_remove = 'remove_file';
    protected $model;

    public function __construct() {
        parent::__construct();
        PlMenu::setActive('files');
        Breadcrumb::add(trans('admin::view.files'), route('admin::file.index'));
        $this->model = FileModel::class;
    }

    public function index(Request $request) {
        canAccess('view_file');
        
        $files = FileModel::getData($request->all());
        if($request->wantsJson() || $request->ajax()){
            return response()->json($files);
        }
        return view('admin::file.index', ['items' => $files]);
    }
    
    public function dialog(Request $request){
        canAccess('view_file');
        
        $params = $request->all();
        return view('admin::file.dialog', compact('params'));
    }
    
    public function manage(){
        canAccess('view_file');
        
        return view('admin::file.manage');
    }
    
    public function show($id, Request $request){
        canAccess('view_file', FileModel::getAuthorId($id));
        
        $size = 'thumbnail';
        if($request->has('size')){
            $size = $request->get('size');
        }
        $file = FileModel::find($id);
        if ($file) {
            return $file->getImage($size);
        }
        return null;
    }

    public function create() {
        canAccess($this->cap_create);
        
        Breadcrumb::add(trans('admin::view.create'));
        return view('admin::file.create');
    }

    public function store(Request $request) {
        canAccess($this->cap_create);
        
        if (!$request->hasFile('files')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(trans('validation.required', ['attribute' => 'file']), 422);
            }
            return redirect()->back()->withInput()->withErrors(['file' => trans('validation.required', ['attribute' => 'file'])]);
        }

        $files = $request->file('files');
        $results = [];

        try {
            foreach ($files as $file) {
                $newfile = FileModel::insertData($file);
                $newfile->thumb_url = $newfile->getSrc('thumbnail');
                $newfile->full_url = $newfile->getSrc('full');
                array_push($results, $newfile);
            }
        } catch (PlException $ex) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($ex->getError(), 422);
            }
            return redirect()->back()->withInput()->withErrors($ex);
        }
            
        if($request->wantsJson() || $request->ajax()){
            return response()->json($results);
        }
        return redirect()->back()->with('succ_mess', trans('admin::message.store_success'));
    }

    public function edit($id) {
        canAccess($this->cap_edit, FileModel::getAuthorId($id));
        
        Breadcrumb::add(trans('admin::view.edit'));
        $item = FileModel::findOrFail($id);
        $users = null;
        if(cando('manage_files')){
            $users = User::getData()->lists('name', 'id')->toArray();
        }
        return view('admin::file.edit', compact('item', 'users'));
    }

}
