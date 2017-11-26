<?php

namespace App\Facades\Classes;

use App\Models\Tax;

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
    
    public function getMenuItems($slug)
    {
        $menuCat = Tax::find($slug);
        dd($menuCat);
        if (!$menuCat) {
            return null;
        }
        $menuItems = $menuCat->menuItems()
                ->joinLang()
                ->select('menus.*', 'md.*')
                ->orderBy('order', 'asc')
                ->get();
        dd($menuItems);
    }
    
    public function renderFront($items)
    {
        $html = '<nav class="navbar navbar-expand-lg navbar-light bg-light">'
                . '<a class="navbar-brand" href="#">Navbar</a>'
                . '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">'
                    . '<span class="navbar-toggler-icon"></span>'
                . '</button>';
        $html .= '<div class="collapse navbar-collapse" id="navbarSupportedContent">'
                    . '<ul class="navbar-nav mr-auto">';
        
        
        
        $html .=    '</ul>'
                . '</div>';
        
        '<div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dropdown
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" href="#">Disabled</a>
            </li>
          </ul>
          <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
      </nav>';
    }
    
}

