<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cache;
use PlOption;

class CacheFunc
{
    const CACHE_TIME = 3600;
    
    public static function put($key, $value, $time = null)
    {
        if (!$time) {
            $time = self::CACHE_TIME;
        }
        Cache::put(md5($key), $value, $time);
    }
    
    public static function get($key, $default = null)
    {
        $value = Cache::get(md5($key));
        if ($value === null) {
            $value = $default;
        }
        return $value;
    }
    
    public static function forget($key)
    {
        Cache::forget(md5($key));
    }
    
    public static function forgetMenus()
    {
        $id = PlOption::get('primary_menu'); 
        if (!$id) {
            return;
        }
        $key = 'menu_items_' . $id . '_';
        $langs = getLangs();
        foreach ($langs as $lang) {
            self::forget($key . $lang->code);
        }
    }
}
