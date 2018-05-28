<?php

namespace Admin\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File as FileModel;
use Admin\Facades\AdConst;

class AjaxController extends Controller
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }
    
    public function action(){
        $action = $this->request->get('action');
        $result = [];
        switch ($action) {
            case 'load_files':
                $data = $this->request->all();
                $data['per_page'] = AdConst::FILE_PER_PAGE;
                $files = FileModel::getData($data);
                $result['html'] = '';
                if (!$files->isEmpty()){
                    foreach ($files as $file) {
                        $result['html'] .= view('admin::file.file-item', ['file' => $file])->render();
                    }
                }
                $result['current_page'] = $files->currentPage();
                $result['next_page_url'] = $files->nextPageUrl();
                break;
            case 'get_video_info':
                if (!$this->request->has('link')) {
                    return response()->json(trans('message.no_data'), 422);
                }
                break;
        }
        return $result;
    }
}

