<?php

namespace Admin\Facades;

class PlMenu {
    
    protected $actives = [];
    
    public function setActive ($active) {
        if (!is_array($active)) {
            $active = (array) $active;
        }
        foreach ($active as $str) {
            array_push($this->actives, $str);
        }
    }
    
    public function renderMenus ($items, $depth = 0) {
        $html = '<ul '. ($depth == 0 ? 'class="sidebar-menu" data-widget="tree"' : 'class="treeview-menu"') .'>';
        foreach ($items as $item) {
            if (canDo($item['cap'])) {
                $hasChilds = (isset($item['childs']) && $item['childs']);
                $liClass = '';
                if ($hasChilds) {
                    $liClass .= 'treeview';
                }
                if (in_array($item['active'], $this->actives)) {
                    $liClass .= ' active';
                }
                $html .= '<li class="'. $liClass .'">';
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
                    $html .= $this->renderMenus($item['childs'], $depth + 1);
                }
                $html .= '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }
    
}

