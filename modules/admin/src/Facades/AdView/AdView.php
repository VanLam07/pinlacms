<?php

namespace Admin\Facades\AdView;

class AdView {
    
    const GD_UNDEFINED = 0;
    const GD_MALE = 1;
    const GD_FEMALE = 2;
    
    public function getGendersList() {
        return [
            self::GD_UNDEFINED => trans('admin::view.Undefined'),
            self::GD_MALE => trans('admin::view.Male'),
            self::GD_FEMALE => trans('admin::view.Female')
        ];
    }
    
}

