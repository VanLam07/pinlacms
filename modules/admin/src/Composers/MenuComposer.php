<?php

namespace Admin\Composers;
use Admin\Facades\AdConst;

class MenuComposer {
    
    public function compose($view) {
        
        $menus = [
            [
                'name' => 'Dashboard',
                'url' => route('admin::index'),
                'cap' => 'accept_manage',
                'icon' => 'fa-dashboard'
            ],
            [
                'name' => trans('admin::view.posts'), 
                'url' => '#',
                'cap' => 'view_post', 
                'icon' => 'fa-newspaper-o',
                'childs' => [
                    [
                        'name' => trans('admin::view.all'),
                        'url' => route('admin::post.index', ['status' => AdConst::STT_PUBLISH]),
                        'cap' => 'view_post'
                    ],
                    [
                        'name' => trans('admin::view.create'),
                        'url' => route('admin::post.create'),
                        'cap' => 'publish_post',
                    ]
                ]
            ],
            [
                'name' => trans('admin::view.pages'), 
                'url' => route('admin::page.index', ['status' => AdConst::STT_PUBLISH]),
                'cap' => 'view_post', 
                'icon' => 'fa-copy'
            ],
//            [
//                'name' => trans('admin::view.comments'), 
//                'url' => route('admin::comment.index', ['status' => AdConst::STT_PUBLISH]),
//                'cap' => 'view_comment', 
//                'icon' => 'fa-comments'
//            ],
            [
                'name' => trans('admin::view.cats'), 
                'url' => route('admin::cat.index'), 
                'cap' => 'manage_cats', 
                'icon' => 'fa-folder'
            ],
            [
                'name' => trans('admin::view.tags'), 
                'url' => route('admin::tag.index'), 
                'cap' => 'manage_tags', 
                'icon' => 'fa-tags'
            ],
            [
                'name' => trans('admin::view.medias'), 
                'url' => route('admin::media.index', ['status' => AdConst::STT_PUBLISH]),
                'cap' => 'view_post', 
                'icon' => 'fa-file-image-o'
            ],
            [
                'name' => trans('admin::view.albums'), 
                'url' => route('admin::album.index'), 
                'cap' => 'manage_cats', 
                'icon' => 'fa-image'
            ],
            [
                'name' => trans('admin::view.files'), 
                'url' => route('admin::file.index'), 
                'cap' => 'view_file', 
                'icon' => 'fa-file'
            ],
            [
                'name' => trans('admin::view.menucats'), 
                'url' => route('admin::menucat.index'), 
                'cap' => 'manage_menus', 
                'icon' => 'fa-bars'
            ],
            [
                'name' => trans('admin::view.sliders'), 
                'url' => route('admin::slider.index'), 
                'cap' => 'manage_cats', 
                'icon' => 'fa-sliders'
            ],
            [
                'name' => trans('admin::view.users'), 
                'url' => route('admin::user.index', ['status' => AdConst::STT_PUBLISH]),
                'cap' => 'view_user', 
                'icon' => 'fa-user'
            ],
            [
                'name' => trans('admin::view.roles'), 
                'url' => route('admin::role.index'), 
                'cap' => 'manage_roles', 
                'icon' => 'fa-users'
            ],
            [
                'name' => trans('admin::view.caps'), 
                'url' => route('admin::cap.index'), 
                'cap' => 'manage_caps', 
                'icon' => 
                'fa-sign-in'
            ],
            [
                'name' => trans('admin::view.langs'), 
                'url' => route('admin::lang.index', ['status' => AdConst::STT_PUBLISH]),
                'cap' => 'manage_langs', 
                'icon' => 'fa-language'
            ],
//            [
//                'name' => trans('admin::view.options'), 
//                'url' => route('admin::option.index'), 
//                'cap' => 'manage_options', 
//                'icon' => 'fa-gear'
//            ]
        ];

        $view->with('menuList', $menus);
        
    }
    
}

