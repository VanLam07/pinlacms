<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Admin\Facades\AdConst;
use Illuminate\Validation\ValidationException;
use Exception;
use Validator;

class BaseModel extends Model {
    
    public function isUseSoftDelete() {
        return false;
    }
    
    public function validator(array $attrs, array $rule = [], array $message = []) {
        $valid = Validator::make($attrs, 
                $rule ? $rule : $this->rules(), 
                $message);
        if ($valid->fails()) {
            throw new ValidationException($valid, 422);
        }
        return true;
    }
    
    public function getData($data) {
        $opts = [
            'fields' => ['*'],
            'orderby' => 'created_at',
            'order' => 'desc',
            'per_page' => AdConst::PER_PAGE,
            'status' => [],
            'exclude_key' => 'id',
            'exclude' => [],
            'page' => 1,
            'filters' => [],
        ];
        
        $opts = array_merge($opts, $data);
        
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
                    if ($value) {
                        $collection->where($key, 'like', "%$value%");
                    }
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
    
    public function forceDeleteData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $items = self::withTrashed()
                ->whereIn('id', $ids)->get();
        if (!$items->isEmpty()) {
            foreach ($items as $item) {
                $item->forceDelete();
            }
        }
    }
    
    public function restoreData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::whereIn('id', $ids)
                ->restore();
    }

    public function actions($request) {
        $valid = Validator::make($request->all(), [
            'action' => 'required',
            'item_ids.*' => 'required' 
        ]);
        if ($valid->fails()) {
            throw new ValidationException($valid, 422);
        }

        $item_ids = $request->input('item_ids');
        if (!$item_ids) {
            throw new Exception(trans('admin::message.no_item'));
        }
        $action = $request->input('action');
        switch ($action) {
            case 'restore':
                $this->restoreData($item_ids);
            case 'publish':
                $this->changeStatus($item_ids, AdConst::STT_PUBLISH);
                break;
            case 'draft': 
                $this->changeStatus($item_ids, AdConst::STT_DRAFT);
                break;
            case 'trash':
                $this->destroyData($item_ids);
                break;
                break;
            case 'delete':
                if ($this->isUseSoftDelete()) {
                    $this->forceDeleteData($item_ids);
                } else {
                    $this->destroyData($item_ids);
                }
                break;
            case defalt:
                break;
        }
    }

}
