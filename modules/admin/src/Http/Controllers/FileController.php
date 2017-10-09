<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\File as FileModel;
use Illuminate\Validation\ValidationException;
use App\User;
use PlMenu;

class FileController extends BaseController {

    protected $model;
    protected $user;

    public function __construct(FileModel $file, User $user) {
        PlMenu::setActive('files');

        $this->model = $file;
        $this->user = $user;
    }

    public function index(Request $request) {
        canAccess('view_file');
        
        $files = $this->model->getData($request->all());
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
        canAccess('view_file', $this->model->getAuthorId($id));
        
        $size = 'thumbnail';
        if($request->has('size')){
            $size = $request->get('size');
        }
        $file = $this->model->find($id);
        if ($file) {
            return $file->getImage($size);
        }
        return null;
    }

    public function create() {
        canAccess('publish_file');
        
        return view('admin::file.create');
    }

    public function store(Request $request) {
        canAccess('publish_file');
        
        if (!$request->hasFile('files')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([], 422);
            }
            return redirect()->back()->withInput()->withErrors(['file' => trans('validation.required', ['attribute' => 'file'])]);
        }

        $files = $request->file('files');
        $results = [];

        try {
            foreach ($files as $file) {
                $newfile = $this->model->insertData($file);
                $newfile->thumb_url = $newfile->getSrc('thumbnail');
                $newfile->full_url = $newfile->getSrc('full');
                array_push($results, $newfile);
            }
        } catch (ValidationException $ex) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($ex, 422);
            }
            return redirect()->back()->withInput()->withErrors($ex);
        } catch (\Exception $ex) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json($ex->getMessage(), 500);
            }
        }
            
        if($request->wantsJson() || $request->ajax()){
            return response()->json($results);
        }
        return redirect()->route('admin::file.index')->with('succ_mess', trans('admin::message.store_success'));
    }

    public function edit($id) {
        canAccess('edit_file', $this->model->getAuthorId($id));
        
        $item = $this->model->findOrFail($id);
        $users = null;
        if(cando('manage_files')){
            $users = $this->user->getData()->lists('name', 'id')->toArray();
        }
        return view('admin::file.edit', compact('item', 'users'));
    }

    public function update($id, Request $request) {
        canAccess('edit_file', $this->model->getAuthorId($id));
        
        try {
            $this->model->updateData($id, $request->all());
            return redirect()->back()->with('succ_mess', trans('admin::message.update_success'));
        } catch (ValidationException $ex) {
            return redirect()->back()->withInput()->withErrors($ex->errors());
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('error_mess', $ex->getMessage());
        }
    }

}
