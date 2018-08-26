<?php

namespace Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PostType;
use App\Models\Tax;
use App\Models\File as FileModel;

class ApiController extends Controller {

    protected $post;
    protected $tax;
    protected $file;
    protected $request;

    public function __construct(Request $request) 
    {
        $this->request = $request;
    }

    public function getPosts() {
        $posts = PostType::getData('post', $this->request->all());
        return response()->json($posts);
    }

    public function getPages() {
        $pages = PostType::getData('page', $this->request->all());
        return response()->json($pages);
    }

    public function getCats() {
        $cats = Tax::getData('cat', $this->request->all());
        return response()->json($cats);
    }
    
    public function getAlbums()
    {
        $albums = Tax::getData('album', $this->request->all());
        return response()->json($albums);
    }

    public function getFiles() {
        $files = FileModel::getData($this->request->all());
        return response()->json($files);
    }

}
