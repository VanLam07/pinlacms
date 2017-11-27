<?php

namespace App\Facades\Lang;

use App\Models\Lang as LangModel;

class Locale{
    
    public function all(){
        return LangModel::allLangs();
    }
    
    public function allCodes() {
        return LangModel::getAllCodes();
    }
    
    public function getCurrent(){
        return LangModel::getCurrent(['name', 'code', 'icon']);
    }
    
    public function findByCode($code, $fields=['*']){
        return LangModel::findByCode($code, $fields);
    }
    
    public function has($code){
        $allCode = $this->allCodes();
        return in_array($code, $allCode);
    }
    
}

