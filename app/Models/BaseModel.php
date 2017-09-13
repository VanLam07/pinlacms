<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Validator;

class BaseModel extends Model {
    
    protected $error;

    const STT_ACTIVE = 1;
    const STT_DISABLE = 2;
    const STT_BANNED = 3;
    const STT_TRASH = 0;
    const PER_PAGE = 20;

    public function validator(array $attrs, array $rule = [], array $message = []) {
        $valid = Validator::make($attrs, ($rule) ? $rule : $this->rules(), $message);
        if ($valid->fails()) {
            $this->error = $valid->errors();
            throw new Exception($this->error, 422);
        }
        return true;
    }
    
    public function getError() {
        return $this->error;
    }
  
    
    public function getData($data) {
        $opts = [
            'fields' => ['*'],
            'orderby' => 'created_at',
            'order' => 'desc',
            'per_page' => self::PER_PAGE,
            'exclude_key' => 'id',
            'exclude' => [],
            'page' => 1,
            'filters' => []
        ];
        
        $opts = array_merge($opts, $data);
        
        $result = self::select($opts['fields']);
        if ($opts['exclude']) {
            $result->whereNotIn($opts['exclude_key'], $opts['exclude']);
        }
        if ($opts['filters']) {
            $this->filterData($result, $opts['filters']);
        }
        $result->orderby($opts['orderby'], $opts['order']);
        
        if($opts['per_page'] == -1){
            return $result->get();
        }
        return $result->paginate($opts['per_page']);
    }
    
    public function filterData(&$collection, $filters) {
        if ($filters && is_array($filters)) {
            foreach ($filters as $key => $value) {
                if (is_array($value)) {
                    $collection->whereIn($key, $value);
                } else {
                    $collection->where($key, 'like', "%$value%");
                }
            }
        }
    }

    public function get_author_id($id, $author_field = 'author_id') {
        $item = self::find($id, [$author_field]);
        if ($item) {
            return $item->$author_field;
        }
        return 0;
    }

    public function insertData($data) {
        $this->validator($data, $this->rules());
        
        return self::create($data);
    }

    public function updateData($id, $data) {
        $this->validator($data, $this->rules($id));

        if (isset($data['time'])) {
            $time = $data['time'];
            $date = date('Y-m-d', strtotime($time['year'] . '-' . $time['month'] . '-' . $time['day']));
            $data['created_at'] = $date;
        }
        $fillable = self::getFillable();
        $data = array_only($data, $fillable);
        return self::where('id', $id)->update($data);
    }

    public function changeStatus($ids, $status) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::whereIn('id', $ids)->update(['status' => $status]);
    }

    public function destroyData($ids) {
        return self::destroy($ids);
    }

    public function actions($request) {
        $valid = Validator::make($request->all(), [
            'action' => 'required',
            'item_ids.*' => 'required' 
        ]);
        if ($valid->fails()) {
            $this->error = $valid->errors();
            throw new Exception($this->error, 422);
        }

        $item_ids = $request->input('item_ids');
        if (!$item_ids) {
            $this->error = trans('message.no_item');
            throw new Exception($this->error);
        }
        $action = $request->input('action');
        switch ($action) {
            case 'restore':
                $this->changeStatus($item_ids, STT_ACTIVE);
                break;
            case 'ban':
                $this->changeStatus($item_ids, STT_BANNED);
                break;
            case 'trash':
            case 'delete':
                $this->changeStatus($item_ids, STT_TRASH);
                break;
            case 'disable':
                $this->changeStatus($item_ids, STT_DISABLE);
                break;
            case 'remove':
                $this->destroyData($item_ids);
                break;
        }
    }

}
