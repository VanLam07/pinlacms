<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Comment;

class CommentController extends BaseController
{
    protected $model;

    public function __construct(Comment $comment) {
        $this->model = $comment;
    }
    
    public function index(Request $request){
        $items = $this->model->getData($request->all());
        return view('admin::comment.index', compact('items'));
    }
    
    public function create(){
//        canAccess('publish_comments');
        
        $parents = $this->model->getData([
            'fields' => ['id', 'parent_id'],
            'per_page' => -1,
            'orderby' => 'id'
        ]);
        return view('admin::comment.create', compact('parents'));
    }
    
    public function store(Request $request){
//        canAccess('publish_comments');
        
        return parent::store($request);
    }
    
    public function edit($id){
//        canAccess('edit_my_comment', $this->model->get_author_id($id));
        
        $parents = $this->model->getData([
            'fields' => ['id', 'parent_id'],
            'per_page' => -1,
            'orderby' => 'id',
            'exclude' => [$id]
        ]);
        $item = $this->model->find($id); 
        return view('admin::comment.edit', compact('item', 'parents'));
    }
    
    public function update($id, Request $request){
//        canAccess('edit_my_comment', $this->model->get_author_id($id));
        
        return parent::update($id, $request);
    }

    public function multiAction(Request $request){
//        if(!cando('remove_other_comments')){
//            return redirect()->back()->withInput()->with('error_mess', trans('auth.authorize'));
//        }

        return parent::multiActions($request);
    }
    
}
