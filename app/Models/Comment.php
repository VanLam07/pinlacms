<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Admin\Facades\AdConst;

class Comment extends BaseModel {

    protected $table = 'comments';
    protected $fillable = ['post_id', 'author_email', 'author_name', 'author_id', 'author_ip', 'content', 'status', 'agent', 'parent_id'];
    protected $capCreate = 'publish_comment';
    protected $capEdit = 'edit_comment';
    protected $capRemove = 'remove_comment';

    use SoftDeletes;
    
    public function isUseSoftDelete() {
        return true;
    }

    public function post() {
        return $this->belongsTo('\App\Models\PostType', 'post_id', 'id');
    }
    
    public function getPost($lang=null){
        $lang = $lang ? $lang : currentLocale();
        return $this->post()
                ->join('post_desc as pd', 'posts.id', '=', 'pd.post_id')
                ->where('pd.lang_code', '=', $lang)
                ->select('pd.title', 'pd.slug', 'pd.post_id');
    }
    
    public function str_status(){
        if($this->status == 1){
            return trans('manage.enable');
        }
        return trans('manage.disable');
    }
    
    public function rules($update = false) {
        return [
            'content' => 'required',
            'post_id' => 'required'
        ];
    }

    public function getData($args = []) {
        $opts = [
            'fields' => ['*'],
            'status' => [AdConst::STT_PUBLISH],
            'orderby' => 'created_at',
            'order' => 'desc',
            'per_page' => AdConst::PER_PAGE,
            'exclude_key' => 'id',
            'exclude' => [],
            'filters' => [],
            'post_id' => null,
            'author_id' => null
        ];
        $opts = array_merge($opts, $args);
        
        $result = self::select($opts['fields']);
        
        if ($opts['status']) {
            if (!is_array($opts['status'])) {
                $opts['status'] = [$opts['status']];
            }
            if ($opts['status'][0] == AdConst::STT_TRASH) {
                $result->onlyTrashed();
            } else {
                $result->whereIn('status', $opts['status']);
            }
        }
        
        if ($opts['post_id']) {
            $result = $result->where('post_id', $opts['post_id']);
        }

        if ($opts['author_id']) {
            $result = $result->where('author_id', $opts['author_id']);
        }
        
        if ($opts['exclude']) {
            $result = $result->whereNotIn($opts['exclude_key'], $opts['exclude']);
        }
        
        if ($opts['filters']) {
            $this->filterData($result, $opts['filters']);
        }
        
        $result->orderBy($opts['orderby'], $opts['order']);

        if ($opts['per_page'] > -1) {
            $result->paginate($opts['per_page']);
        }
        return $result->get();
    }

    public function insertData($data) {
        $this->validator($data, $this->rules());

        if (isset($data['author_id'])) {
            $author = User::find($data['author_id']);
            if($author){
                $data['author_email'] = $author->email;
                $data['author_name'] = $author->name;
            }
        }else{
            $user = auth()->user();
            $data['author_id'] = $user->id;
            $data['author_email'] = $user->email;
            $data['author_name'] = $user->name;
        }
        if(!isset($data['status'])){
            $data['status'] = 1;
        }
        $data['agent'] = request()->header('User-Agent');
        $data['author_ip'] = request()->ip();
        if (!isset($data['parent_id']) || !$data['parent_id']) {
            $data['parent_id'] = null;
        }
        $item = self::create($data);
        return $item->post()->increment('comment_count');
    }

    public function updateData($id, $data) {
        $this->validator($data, $this->rules($id));

        if (isset($data['time'])) {
            $time = $data['time'];
            $date = date('Y-m-d', strtotime($time['year'] . '-' . $time['month'] . '-' . $time['day']));
            $data['created_at'] = $date;
        }
        if (isset($data['author_id'])) {
            $author = User::find($data['author_id']);
            if($author){
                $data['author_email'] = $author->email;
                $data['author_name'] = $author->name;
            }
        }
        if (!isset($data['parent_id']) || !$data['parent_id']) {
            $data['parent_id'] = null;
        }
        $fillable = $this->getFillable();
        $data = array_only($data, $fillable);
        return self::where('id', $id)->update($data);
    }
    
    public function forceDeleteData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $items = self::withTrashed()
                ->whereIn('id', $ids)->get();
        if (!$items->isEmpty()) {
            foreach ($items as $item) {
                $item->post()->decrement('comment_count');
                $item->forceDelete();
            }
        }
    }
}
