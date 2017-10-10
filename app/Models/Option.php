<?php

namespace App\Models;

use Admin\Facades\AdConst;
use DB;

class Option extends BaseModel
{
    protected $table = 'options';
    protected $primaryKey = 'option_id';
    
    public $incrementing = false; 

    protected $fillable = ['option_key', 'value', 'label', 'lang_code'];
    
    public $timestamps = false;
    
    public function rules(){
        return [
            'option_key' => 'required|alpha_dash',
            'value' => 'required'
        ];
    }
    
    public function getData($args=[]){
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
    
    public function destroyData($ids) {
        return self::destroy($ids);
    }
}
