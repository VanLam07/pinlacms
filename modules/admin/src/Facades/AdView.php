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
    
    public function nestedAdminMenus($items, $depth = 0) {
        $html = '<ul '. ($depth == 0 ? 'class="sidebar-menu" data-widget="tree"' : 'class="treeview-menu"') .'>';
        foreach ($items as $item) {
            if (canDo($item['cap'])) {
                $hasChilds = (isset($item['childs']) && $item['childs']);
                $html .= '<li'. ($depth == 0 ? ' class="treeview"' : '') .'>';
                $html .= '<a href="'. $item['url'] .'" title="'.$item['name'].'">'
                            . '<i class="fa ' . (isset($item['icon']) ? $item['icon'] : 'fa-circle-o') . '"></i> '
                            . '<span>' . $item['name'] . '</span> ';
                if($hasChilds) {
                    $html .= '<span class="pull-right-container">'
                                .'<i class="fa fa-angle-left pull-right"></i>'
                            .'</span>';
                }
                $html .= '</a>';
                if ($hasChilds) {
                    $html .= $this->nestedAdminMenus($item['childs'], $depth + 1);
                }
                $html .= '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }
    
}

