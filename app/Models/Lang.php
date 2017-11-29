<?php

namespace App\Models;

use App\Helper\CacheFunc;
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
        
        if (($allCodes = CacheFunc::get(self::KC_CODES)) !== null) {
            return $allCodes;
        }
        
        $allCodes =self::where('status', AdConst::STT_PUBLISH)
                ->select('code')
                ->pluck('code')
                ->toArray();
        
        CacheFunc::put(self::KC_CODES, $allCodes);
        
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
        if (($allLangs = CacheFunc::get(self::KC_LANGS)) !== null) {
            return $allLangs;
        }
        $allLangs = self::getData([
            'fields' => ['code', 'icon', 'name'],
            'per_page' => -1,
            'to_array' => true
        ]);
        CacheFunc::put(self::KC_LANGS, $allLangs);
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
        return [
            'name' => 'required',
            'code' => 'required|max:2',
            'icon' => 'required',
            'folder' => 'required',
            'unit' => 'required',
            'ratio_currency' => 'required|numeric'
        ];
    }
    
    public static function insertData($data) {
        if (!isset($data['order'])) {
            $data['order'] = 0;
        }
        return parent::insertData($data);
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
        CacheFunc::forget(self::KC_CODES);
        parent::save($options);
    }

}
