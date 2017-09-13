<?php

namespace Admin\Facades\AdView;

class AdView {
    
    const GD_UNDEFINED = 0;
    const GD_MALE = 1;
    const GD_FEMALE = 2;
    
    const CAP_SELF = 1;
    const CAP_OTHER = 2;
    
    public function getGendersList() {
        return [
            self::GD_UNDEFINED => trans('admin::view.Undefined'),
            self::GD_MALE => trans('admin::view.Male'),
            self::GD_FEMALE => trans('admin::view.Female')
        ];
    }
    
    public function nestedAdminMenus($items, $depth = 0) {
        $html = '<ul '. ($depth == 0 ? 'class="sidebar-menu" data-widget="tree"' : 'class="treeview-menu"') .'>';
        foreach ($items as $item) {
            if (canDo($item['cap'])) {
                $routeParams = (isset($item['route_params'])) ? $item['route_params'] : [];
                $hasChilds = (isset($item['childs']) && $item['childs']);
                $html .= '<li'. ($depth == 0 ? ' class="treeview"' : '') .'>';
                $html .= '<a href="#" title="'.$item['name'].'">'
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

