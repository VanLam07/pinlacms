<?php

namespace Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PostType;
use App\Models\Tax;
use App\Models\File;

class ApiController extends Controller {

    protected $post;
    protected $tax;
    protected $file;
    protected $request;

    public function __construct(
            PostType $post,  
            Tax $tax, 
            File $file, 
            Request $request
    ) {
        $this->post = $post;
        $this->tax = $tax;
        $this->file = $file;
        $this->request = $request;
    }

    public function getPosts() {
        $posts = $this->post->getData('post', $this->request->all());
        return response()->json($posts);
    }

    public function getPages() {
        $pages = $this->post->getData('page', $this->request->all());
        return response()->json($pages);
    }

    public function getCats() {
        $cats = $this->tax->getData('cat', $this->request->all());
        return response()->json($cats);
    }

    public function getFiles() {
        $files = $this->file->getData($this->request->all());
        return response()->json($files);
    }

}
