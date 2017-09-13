<?php

namespace Admin\Composers;

class MenuComposer {
    
    public function compose($view) {
        
        $menus = [
            [
                'name' => 'Dashboard',
                'route' => 'admin::index',
                'cap' => 'accept_manage',
                'icon' => 'fa-dashboard'
            ],
            [
                'name' => trans('admin::view.posts'), 
                'route' => 'admin::post.index', 
                'route_params' => ['status' => 1], 
                'cap' => 'view_post', 
                'icon' => 'fa-newspaper-o',
                'childs' => [
                    [
                        'name' => trans('admin::view.create'),
                        'route' => 'admin::post.create',
                        'cap' => 'publish_post',
                    ]
                ]
            ],
            [
                'name' => trans('admin::view.pages'), 
                'route' => 'admin::page.index', 
                'route_params' => ['status' => 1], 
                'cap' => 'view_post', 
                'icon' => 'fa-copy'
            ],
            [
                'name' => trans('admin::view.comments'), 
                'route' => 'admin::comment.index', 
                'route_params' => ['status' => 1], 
                'cap' => 'view_comment', 
                'icon' => 'fa-comments'
            ],
            [
                'name' => trans('admin::view.cats'), 
                'route' => 'admin::cat.index', 
                'cap' => 'manage_cats', 
                'icon' => 'fa-folder'
            ],
            [
                'name' => trans('admin::view.tags'), 
                'route' => 'admin::tag.index', 
                'cap' => 'manage_tags', 
                'icon' => 'fa-tags'
            ],
            [
                'name' => trans('admin::view.medias'), 
                'route' => 'admin::media.index', 
                'route_params' => ['status' => 1], 
                'cap' => 'view_post', 
                'icon' => 'fa-file-image-o'
            ],
            [
                'name' => trans('admin::view.albums'), 
                'route' => 'admin::album.index', 
                'cap' => 'manage_cats', 
                'icon' => 'fa-image'
            ],
            [
                'name' => trans('admin::view.files'), 
                'route' => 'admin::file.index', 
                'cap' => 'view_file', 
                'icon' => 'fa-file'
            ],
            [
                'name' => trans('admin::view.menucats'), 
                'route' => 'admin::menucat.index', 
                'cap' => 'manage_menus', 
                'icon' => 'fa-bars'
            ],
            [
                'name' => trans('admin::view.sliders'), 
                'route' => 'admin::slider.index', 
                'cap' => 'manage_cats', 
                'icon' => 'fa-sliders'
            ],
            [
                'name' => trans('admin::view.users'), 
                'route' => 'admin::user.index', 
                'route_params' => ['status' => 1], 
                'cap' => 'view_user', 
                'icon' => 'fa-user'
            ],
            [
                'name' => trans('admin::view.roles'), 
                'route' => 'admin::role.index', 
                'cap' => 'manage_roles', 
                'icon' => 'fa-users'
            ],
            [
                'name' => trans('admin::view.caps'), 
                'route' => 'admin::cap.index', 
                'cap' => 'manage_caps', 
                'icon' => 
                'fa-sign-in'
            ],
            [
                'name' => trans('admin::view.langs'), 
                'route' => 'admin::lang.index', 
                'route_params' => ['status' => 1], 
                'cap' => 'manage_langs', 
                'icon' => 'fa-language'
            ],
            [
                'name' => trans('admin::view.options'), 
                'route' => 'admin::option.index', 
                'cap' => 'manage_options', 
                'icon' => 'fa-gear'
            ]
        ];

        $view->with('menuList', $menus);
        
    }
    
}

