<?php

namespace App\Facades\Lang;

use App\Models\Lang;

class Locale{
    
    protected $lang;
    
    public function __construct(Lang $lang) {
        $this->lang = $lang;
    }
    
    public function all($args=[]){
        return $this->lang->getData($args);
    }
    
    public function allCodes() {
        return $this->lang->getAllCodes();
    }
    
    public function getCurrent(){
        return $this->lang->getCurrent(['id', 'name', 'code', 'icon']);
    }
    
    public function findByCode($code, $fields=['*']){
        return $this->lang->findByCode($code, $fields);
    }
    
    public function has($code){
        $allCode = $this->allCodes();
        return in_array($code, $allCode);
    }
    
}

