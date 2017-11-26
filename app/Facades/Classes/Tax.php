<?php

namespace App\Facades\Classes;

use App\Models\Tax as TaxModel;

class Tax {
    
    protected $tax;

    public function __construct(TaxModel $tax) {
        $this->tax = $tax;
    }
    
    public function search($key)
    {
        
    }
    
}
