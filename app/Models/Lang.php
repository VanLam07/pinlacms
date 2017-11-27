<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Admin\Facades\AdConst;

class Lang extends BaseModel {

    protected $table = 'langs';
    protected $fillable = ['name', 'code', 'icon', 'folder', 'unit', 'ratio_currency', 'order', 'status', 'default'];
    protected $primaryKey  = 'code';
    public $timestamps = false;
    public $incrementing  = false;
    
    const KC_CODES = 'all_key_codes';
    const KC_LANGS = 'all_key_langs';
    const KC_CURRENT = 'current_key_langs';
    
    public static function getAllCodes() {
        
        if (($allCodes = Cache::get(self::KC_CODES)) !== null) {
            return $allCodes;
        }
        
        $allCodes =self::where('status', AdConst::STT_PUBLISH)
                ->select('code')
                ->pluck('code')
                ->toArray();
        
        Cache::put(self::KC_CODES, $allCodes, self::CACHE_TIME);
        
        return $allCodes;
    }
    
    public static function getData($args = []) {
        $data = [
            'orderby' => 'order',
            'order' => 'asc',
            'status' => AdConst::STT_PUBLISH
        ];
        $data = array_merge($data, $args);
        return parent::getData($data);
    }
    
    public static function allLangs()
    {
        if (($allLangs = Cache::get(self::KC_LANGS)) !== null) {
            return $allLangs;
        }
        $allLangs = self::getData([
            'fields' => ['code', 'icon', 'name'],
            'per_page' => -1,
            'to_array' => true
        ]);
        Cache::put(self::KC_LANGS, $allLangs, self::CACHE_TIME);
        return $allLangs;
    }

    public function switchUrl() {
        $request = request();
        $locale = $this->code;
        if (!hasLang($locale)) {
            $locale = config('app.fallback_locale');
        }
        app()->setLocale($locale);
        $segments = $request->segments(); 
        $segments[0] = $locale;
        return '/'.implode('/', $segments);
    }

    public function icon($width = 16) {
        if ($this->icon) {
            $src = '/images/flags/' . $this->icon;
            return '<img width="'. $width .'" src="' . $src . '" alt="'. $this->code .'">';
        }
        return null;
    }

    public function status() {
        switch ($this->status) {
            case 1:
                return 'Active';
            case -1:
                return 'Disable';
        }
    }

    public function strDefault() {
        if ($this->default == 1) {
            return 'Yes';
        }
        return 'No';
    }
    
    public static function rules($update = false) {
        if (!$update) {
            return [
                'name' => 'required',
                'code' => 'required',
                'icon' => 'required',
                'folder' => 'required',
                'unit' => 'required',
                'ratio_currency' => 'required|numeric'
            ];
        }
        return [
            'code' => 'required'
        ];
    }

    public static function findByName($name, $fields = ['*']) {
        return self::where('name', $name)->first($fields);
    }
    
    public static function findByCode($code, $fields=['*']){
        return self::where('code', $code)->first($fields);
    }
    
    public static function getCurrent($fields = ['*']){
        $current_locale = app()->getLocale();
        return self::where('code', $current_locale)->first($fields);
    }
    
    public function save(array $options = array()) {
        Cache::forget(self::KC_CODES);
        parent::save($options);
    }

}
