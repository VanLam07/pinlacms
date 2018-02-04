<?php

namespace App\Facades\Classes;

use App\Models\Tax as TaxModel;

class Tax {

    public function search($key)
    {
        
    }
    
    public function listCategories()
    {
        return TaxModel::getData('cat', [
            'fields' => ['taxs.id', 'td.name', 'td.slug', 'taxs.image_id'],
            'per_page' => -1
        ]);
    }
    
}
