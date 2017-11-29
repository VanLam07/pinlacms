<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cache;

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
}
