<?php

namespace App\Models;

use Admin\Facades\AdConst;
use Illuminate\Support\Facades\Cache;
use DB;

class Option extends BaseModel
{
    protected $table = 'options';
    protected $primaryKey = 'option_id';
    
    public $incrementing = false; 

    protected $fillable = ['option_key', 'value', 'label', 'lang_code'];
    
    public $timestamps = false;
    
    const KC_ALL = 'key_cache_all_options';
    const KC_TIME = 24 * 3600;
    
    public static function rules(){
        return [
            'option_key' => 'required|alpha_dash',
            'value' => 'required'
        ];
    }
    
    public static function getData($args=[]){
        $opts = [
            'field' => ['*'],
            'orderby' => 'option_key',
            'order' => 'asc',
            'per_page' => AdConst::PER_PAGE,
            'page' => 1,
            'filters' => []
        ];
        
        $opts = array_merge($opts, $args);
        
        $collection = self::select($opts['field']);
        if ($opts['filters']) {
            $this->filterData($collection, $opts['filters']);
        }
        $collection->orderBy($opts['orderby'], $opts['order']);
        if ($opts['per_page'] > 1) {
            return $collection->paginate($opts['per_page']);
        }
        return $collection->get();
    }
    
    public static function getOption($key, $lang = null) {
        $allValues = Cache::get(self::KC_ALL);
        $allValues = $allValues ? $allValues : [];
        if (isset($allValues[$key])) {
            return $allValues[$key];
        }
        
        $item = self::where('option_key', $key);
        if ($lang) {
            $item->where('lang_code', $lang);
        } else {
            $item->whereNull('lang_code');
        }
        $item = $item->first();
        if ($item) {
            $allValues[$key] = $item->value;
            Cache::put(self::KC_ALL, $allValues, self::KC_TIME);
            return $item->value;
        }
        return null;
    }
    
    public static function destroyData($ids) {
        return self::destroy($ids);
    }
    
    public function save(array $options = array()) {
        Cache::forget($this->kcAll);
        parent::save($options);
    }
}
