<?php

namespace App\Models;

//use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Lang extends BaseModel {

    protected $table = 'langs';
    protected $fillable = ['name', 'code', 'icon', 'folder', 'unit', 'ratio_currency', 'order', 'status', 'default'];
    protected $primaryKey  = 'code';
    public $timestamps = false;
    public $incrementing  = false;
    
    const KC_CODES = 'all_key_codes';
    
    public function getAllCodes() {
        
//        if (($allCodes = Cache::get(self::KC_CODES)) !== null) {
//            return $allCodes;
//        }
        
        $allCodes = DB::table($this->table)
                ->select('code')
                ->pluck('code')
                ->toArray();
        
//        Cache::put(self::KC_CODES, $allCodes);
        
        return $allCodes;
    }
    
    public function getData($args = []) {
        $data = [
            'orderby' => 'order',
            'order' => 'asc',
            'status' => AdConst::STT_PUBLISH
        ];
        $data = array_merge($data, $args);
        return parent::getData($data);
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

    public function icon() {
        if ($this->icon) {
            $src = '/images/flags/' . $this->icon;
            return '<img width="30" src="' . $src . '">';
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
    
    public function rules($update = false) {
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

    function findByName($name, $fields = ['*']) {
        return self::where('name', $name)->first($fields);
    }
    
    public function findByCode($code, $fields=['*']){
        return self::where('code', $code)->first($fields);
    }
    
    public function getCurrent($fields=['*']){
        $current_locale = app()->getLocale();
        return self::where('code', $current_locale)->first($fields);
    }

}
