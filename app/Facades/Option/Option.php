<?php

namespace App\Facades\Option;

use App\Models\Option as OptionModel;
use DB;

class Option{
    protected $option;
    
    public function __construct(OptionModel $option) {
        $this->option = $option;
    }
    
    public function update($key, $value, $lang=null){
        $option = $this->option->where('option_key', $key);
        if ($lang) {
            $option->where('lang_code', $lang);
        } else {
            $option->whereNull('lang_code');
        }
        $option = $option->first();
        if (!$option) {
            $option = new $this->option;
        }
        $option->option_key = $key;
        $option->value = $value;
        $option->lang_code = $lang;
        return $option->save();
    }
    
    public function get($key, $lang=null){
        $item = $this->option->where('option_key', $key);
        if ($lang) {
            $item->where('lang_code', $lang);
        } else {
            $item->whereNull('lang_code');
        }
        if($item){
            return $item->value;
        }
        return null;
    }
}

