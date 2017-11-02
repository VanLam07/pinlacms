<?php

namespace App\Facades\Tax;
use App\Models\Tax;

class Tax{
    
    protected $tax;

    public function __construct(Tax $tax) {
        $this->tax = $tax;
    }
    
    public function query($type='cat', $args=[]){
        return $this->tax->getData($type, $args);
    }
}

