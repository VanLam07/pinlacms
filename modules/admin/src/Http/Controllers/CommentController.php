<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Admin\Http\Controllers\BaseController;
use App\Models\Comment;
use PlMenu;
use Breadcrumb;

class CommentController extends BaseController
{
    protected $cap_create = 'publish_comment';
    protected $cap_edit = 'edit_comment';
    protected $cap_remove = 'remove_comment';
    protected $model;

    public function __construct() {
        parent::__construct();
        Breadcrumb::add(trans('admin::view.comments'), route('admin::comment.index'));
        PlMenu::setActive('comments');
        $this->model = Comment::class;
    }
    
    public function toNested($collection, $parentId = null)
    {
        $nestedItems = [];
        foreach ($collection as $item) {
            if ($item->parent_id == $parentId) {
                $nestedItems[] = $item;
                $nestedItems = array_merge($nestedItems, $this->toNested($collection, $item->id));
            }
        }
        return $nestedItems;
    }
    
    public function index(Request $request){
        canAccess('view_comment');
        
        $data = $request->all();
        $items = Comment::getData($data);
        return view('admin::comment.index', compact('items'));
    }
    
    public function create(){
        canAccess($this->cap_create);
        
        Breadcrumb::add(trans('admin::view.create'));
        $parents = Comment::getData([
            'fields' => ['id', 'parent_id'],
            'per_page' => -1,
            'orderby' => 'id'
        ]);
        return view('admin::comment.create', compact('parents'));
    }
    
    public function store(Request $request){
        canAccess($this->cap_create);
        
        return parent::store($request);
    }
    
    public function edit($id){
        canAccess($this->cap_edit, Comment::getAuthorId($id));
        
        Breadcrumb::add(trans('admin::view.edit'));
        $parents = Comment::getData([
            'fields' => ['id', 'parent_id'],
            'per_page' => -1,
            'orderby' => 'id',
            'exclude' => [$id]
        ]);
        $item = Comment::find($id); 
        return view('admin::comment.edit', compact('item', 'parents'));
    }
    
    public function update($id, Request $request){
        canAccess($this->cap_edit, Comment::getAuthorId($id));
        
        return parent::update($id, $request);
    }
    
}
