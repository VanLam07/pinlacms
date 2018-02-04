<?php

namespace App\Facades\Classes;

use App\Helper\CacheFunc;
use App\Models\Menu as MenuModel;
use App\Models\Tax as TaxModel;

class Menu {
    
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
                if ($hasChilds) {
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
    
    public function getMenuItems($id)
    {
        $cacheKey = 'menu_items_' . $id . '_' . currentLocale();
        if (($menuItems = CacheFunc::get($cacheKey)) != null) {
            return $menuItems;
        }
        
        $menuItems = MenuModel::getData([
            'fields' => ['menus.id', 'menus.parent_id', 'menus.menu_type', 'menus.type_id', 'menus.icon',
                'md.lang_code', 'md.title', 'md.slug', 'md.link'],
            'group_id' => $id,
            'per_page' => -1
        ]);
        $menuItems = TaxModel::toNested($menuItems);
        
        if ($menuItems) {
            CacheFunc::put($cacheKey, $menuItems);
        }
        return $menuItems;
    }
    
    public function renderFrontMenu($menuId)
    {
        $menuItems = $this->getMenuItems($menuId);
        if (!$menuItems) {
            return null;
        }
        return $this->renderFront($menuItems);
    }
    
    public function renderFront($items, $depth = 0)
    {
        $html = '<ul class="'. ($depth > 0 ? 'dropdown-menu' : 'navbar-nav') . '">';
        foreach ($items as $item) {
            $hasChilds = (isset($item['childs']) && $item['childs']);
            if (!$hasChilds) {
                $html .= '<li class="nav-item">';
                $html .= '<a href="'. $item->link .'" target="'. $item->open_type .'" title="' . $item->title . '" class="nav-link">' . $item->title . '</a>';
                $html .= '</li>';
            } else {
                $html .= '<li class="nav-item dropdown">';
                $html .= '<a href="'. $item->link .'" target="'. $item->open_type .'" title="' . $item->title . '" class="nav-link" data-toggle="dropdown">' 
                        . $item->title;
                $html .= ' <i class="fa fa-caret-down"></i>';
                $html .= '</a>';
                $html .= $this->renderFront($item->childs, $depth + 1);
                $html .= '</li>';
            }
        }
        
        $html .= '</ul>';
        
        return $html;
    }
    
}

