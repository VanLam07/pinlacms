<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Admin\Facades\AdConst;
use App\Exceptions\PlException;
use Exception;
use Validator;
use PlOption;

class BaseModel extends Model {
    
    protected $capCreate = 'publish_post';
    protected $capEdit = 'edit_post';
    protected $capRemove = 'remove_post';

    const CACHE_TIME = 3600; //minutes
    
    public static function isUseSoftDelete() {
        return false;
    }
    
    public static function validator(array $attrs, array $rule = [], array $message = []) {
        $valid = Validator::make($attrs, 
                $rule ? $rule : self::rules(), 
                $message);
        if ($valid->fails()) {
            throw new PlException($valid->messages(), 422);
        }
        return true;
    }
    
    public static function getData($data) {
        $perPage = PlOption::get('per_page');
        $opts = [
            'fields' => ['*'],
            'orderby' => 'created_at',
            'order' => 'desc',
            'per_page' => $perPage ? $perPage : AdConst::PER_PAGE,
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
            self::filterData($result, $opts['filters']);
        }
        $result->orderby($opts['orderby'], $opts['order']);
        
        if($opts['per_page'] == -1){
            return $result->get();
        }
        return $result->paginate($opts['per_page']);
    }
    
    public static function filterData(&$collection, $filters) {
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

    public static function getAuthorId($id, $author_field = 'author_id') {
        $item = self::find($id, [$author_field]);
        if ($item) {
            return $item->$author_field;
        }
        return 0;
    }
    
    public function authorId() {
        return $this->author_id;
    }

    public static function insertData($data) {
        self::validator($data, self::rules());
        
        return self::create($data);
    }

    public static function updateData($id, $data) {
        self::validator($data, self::rules($id));

        $itemUpdate = self::findOrFail($id);
        if (isset($data['time'])) {
            $time = $data['time'];
            $date = date('Y-m-d', strtotime($time['year'] . '-' . $time['month'] . '-' . $time['day']));
            $data['created_at'] = $date;
        }
        $hasDel = false;
        if (isset($data['status']) && $data['status'] == AdConst::STT_TRASH) {
            unset($data['status']);
            $hasDel = true;
        }
        $fillable = $itemUpdate->getFillable();
        $data = array_only($data, $fillable);
        $itemUpdate->update($data);
        if ($hasDel) {
            $itemUpdate->delete();
        }
        return $itemUpdate;
    }

    public static function changeStatus($ids, $status, $primaryKey = 'id') 
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        if ($status == AdConst::STT_TRASH) {
            self::whereIn($primaryKey, $ids)->delete();
        }
        return self::whereIn($primaryKey, $ids)->update(['status' => $status]);
    }

    public static function destroyData($ids) 
    {
        return self::destroy($ids);
    }
    
    public static function forceDeleteData($ids) {
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
    
    public static function restoreData($ids) {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        return self::whereIn('id', $ids)
                ->restore();
    }

    public static function actions($request) {
        $valid = Validator::make($request->all(), [
            'action' => 'required',
            'item_ids.*' => 'required' 
        ]);
        if ($valid->fails()) {
            throw new PlException($valid->message(), 422);
        }

        $item_ids = $request->input('item_ids');
        if (!$item_ids) {
            throw new Exception(trans('admin::message.no_item'));
        }
        
        $action = $request->input('action');
        switch ($action) {
            case 'restore':
                self::restoreData($item_ids);
            case 'publish':
                self::changeStatus($item_ids, AdConst::STT_PUBLISH);
                break;
            case 'draft': 
                self::changeStatus($item_ids, AdConst::STT_DRAFT);
                break;
            case 'trash':
                self::destroyData($item_ids);
                break;
            case 'delete':
                if (self::isUseSoftDelete()) {
                    self::forceDeleteData($item_ids);
                } else {
                    self::destroyData($item_ids);
                }
                break;
            case defalt:
                break;
        }
    }
    
    public function save(array $options = array()) {
        parent::save($options);
    }
    
    public static function destroy($ids) {
        parent::destroy($ids);
    }
    
    public function delete() {
        parent::delete();
    }

}
