<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Option;
use App\Models\File;
use Illuminate\Validation\ValidationException;
use Exception;
use PlMenu;

class OptionController extends BaseController
{
    protected $model;
    protected $file;
    protected $cap_accept = 'manage_options';

    public function __construct(Option $option, File $file) {
        PlMenu::setActive('options');
        
        $this->model = $option;
        $this->file = $file;
    }
    
    public function index(Request $request){
        canAccess($this->cap_accept);
        
        $options = $this->model->getData($request->all());
        return view('admin::option.index', ['items' => $options]);
    }
    
    public function create() {
        canAccess($this->cap_accept);
        
        return view('admin::option.create');
    }
    
    
    public function edit($id) {
        canAccess($this->cap_accept);
        
        $item = $this->model->findOrFail($id);
        return view('admin::option.edit', compact('item'));
    }

}
