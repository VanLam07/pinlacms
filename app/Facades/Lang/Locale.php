<?php

namespace App\Facades\Lang;

use App\Models\Lang;

class Locale{
    
    protected $lang;
    
    public function __construct(Lang $lang) {
        $this->lang = $lang;
    }
    
    public function all(){
        return $this->lang->allLangs();
    }
    
    public function allCodes() {
        return $this->lang->getAllCodes();
    }
    
    public function getCurrent(){
        return $this->lang->getCurrent(['name', 'code', 'icon']);
    }
    
    public function findByCode($code, $fields=['*']){
        return $this->lang->findByCode($code, $fields);
    }
    
    public function has($code){
        $allCode = $this->allCodes();
        return in_array($code, $allCode);
    }
    
}

