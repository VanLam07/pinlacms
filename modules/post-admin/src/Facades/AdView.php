<?php

namespace Admin\Facades;

use Admin\Facades\AdConst;

class AdView {
    
    public function getGendersList() {
        return [
            AdConst::GD_UNDEFINED => trans('admin::view.Undefined'),
            AdConst::GD_MALE => trans('admin::view.Male'),
            AdConst::GD_FEMALE => trans('admin::view.Female')
        ];
    }
    
    public function getStatusLabel($hasTrash = true) {
        $status = [
            AdConst::STT_PUBLISH => trans('admin::view.publish'),
            AdConst::STT_DRAFT => trans('admin::view.draft')
        ];
        if ($hasTrash) {
            $status[AdConst::STT_TRASH] = trans('admin::view.trash');
        }
        return $status;
    }
    
    public function getCapsLevel() {
        return [
            AdConst::CAP_NONE => trans('admin::view.cap_none'),
            AdConst::CAP_SELF => trans('admin::view.cap_self'),
            AdConst::CAP_OTHER => trans('admin::view.cap_other')
        ];
    }
    
    public function commentStatusLabel() {
        return [
            AdConst::CM_STT_OPEN => 'Open',
            AdConst::CM_STT_CLOSE => 'Close'
        ];
    }
    
    function listMenuTypes() {
        return [
            0 => trans('admin::view.custom'),
            1 => trans('admin::view.post'),
            2 => trans('admin::view.page'),
            3 => trans('admin::view.cat'),
            4 => trans('admin::view.tag'),
            5 => trans('admin::view.service')
        ];
    }
    
}

