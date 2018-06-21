<?php

namespace App\Facades\Classes;

use App\Models\Tax as TaxModel;
use Illuminate\Support\Facades\DB;

class Tax {

    public function search($key)
    {
        
    }
    
    public function listCategories($isFeature = null)
    {
        return TaxModel::getData('cat', [
            'fields' => ['taxs.id', 'td.name', 'td.slug', 'taxs.image_id', 'taxs.type'],
            'per_page' => -1,
            'is_feature' => $isFeature,
            'orderby' => 'order',
            'order' => 'asc'
        ]);
    }

    public function listTagsCloud($number = 10)
    {
        $tblPre = DB::getTablePrefix();
        return TaxModel::getData('tag', [
            'fields' => ['taxs.id', 'td.name', 'td.slug', 'taxs.type', DB::raw('COUNT(DISTINCT('. $tblPre .'post_tax.post_id)) as count_post')],
            'per_page' => $number,
            'orderby' => 'count_post',
            'order' => 'desc',
            'count_post' => true
        ]);
    }
    
}
