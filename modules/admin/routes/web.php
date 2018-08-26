<?php

Route::get('/pinlaz1703/clear-cache', function() {
//    Artisan::call('migrate');
//    Artisan::call('db:seed');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    return 'Clear successful';
});

Route::group(['prefix' => 'auth'], function () {

    Route::get('/login', ['as' => 'auth.get_login', 'uses' => 'AuthController@getLogin']);
    Route::post('/login', ['as' => 'auth.post_login', 'uses' => 'AuthController@postLogin']);
    Route::get('/register', ['as' => 'auth.get_register', 'uses' => 'AuthController@getRegister']);
    Route::post('/register', ['as' => 'auth.post_register', 'uses' => 'AuthController@postRegister']);
    Route::get('/forget-password', ['as' => 'auth.get_forget_pass', 'uses' => 'AuthController@getForgetPass']);
    Route::post('/forget-password', ['as' => 'auth.post_forget_pass', 'uses' => 'AuthController@postForgetPass']);
    Route::get('/reset-password', ['as' => 'auth.get_reset_pass', 'uses' => 'AuthController@getResetPass']);
    Route::post('/reset-password', ['as' => 'auth.post_reset_pass', 'uses' => 'AuthController@postResetPass']);

});

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/', ['as' => 'index', 'uses' => 'AdminController@index']);
    Route::get('/search', ['as' => 'search', 'uses' => 'AdminController@search']);
    //Auth
    Route::get('/auth/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
    Route::get('/account/profile/{account?}', ['as' => 'account.profile', 'uses' => 'AuthController@getProfile']);
    Route::post('/account/profile/update', ['as' => 'account.update_profile', 'uses' => 'AuthController@updateProfile']);
    Route::get('/account/change-password', ['as' => 'account.change_pass', 'uses' => 'AuthController@getChangePass']);
    Route::post('/account/update-password', ['as' => 'account.update_pass', 'uses' => 'AuthController@updatePassword']);
    
    //    Capability
    Route::post('/capabilities/actions', ['as' => 'cap.actions', 'uses' => 'CapController@multiActions']);
    Route::resource('capabilities', 'CapController', rsNames('cap'));
    //    Roles
    Route::post('/roles/actions', ['as' => 'role.actions', 'uses' => 'RoleController@multiActions']);
    Route::resource('roles', 'RoleController', rsNames('role'));
    //    Users
    Route::post('/users/actions', ['as' => 'user.actions', 'uses' => 'UserController@multiActions']);
    Route::resource('users', 'UserController', rsNames('user'));
    //    Languages
    Route::resource('languages', 'LangController', rsNames('lang'));
    Route::post('/languages/actions', ['as' => 'lang.actions', 'uses' => 'LangController@multiActions']);
    //    Categories
    Route::resource('categories', 'CatController', rsNames('cat'));
    Route::post('/categories/actions', ['as' => 'cat.actions', 'uses' => 'CatController@multiActions']);
    //    Tags
    Route::resource('tags', 'TagController', rsNames('tag'));
    Route::post('/tags/actions', ['as' => 'tag.actions', 'uses' => 'TagController@multiActions']);
    //    Albums
    Route::resource('albums', 'AlbumController', rsNames('album'));
    Route::post('/albums/actions', ['as' => 'album.actions', 'uses' => 'AlbumController@multiActions']);
    //    Menu cats
    Route::get('/menu-groups/to-nested', ['as' => 'menucat.to_nested', 'uses' => 'MenuCatController@getNestedMenus']);
    Route::post('/menu-groups/store-items', ['as' => 'menucat.store_items', 'uses' => 'MenuCatController@storeItems']);
    Route::post('/menu-groups/update-order-items', ['as' => 'menucat.update_order_items', 'uses' => 'MenuCatController@updateOrderItems']);
    Route::resource('menu-groups', 'MenuCatController', rsNames('menucat'));
    Route::post('/menu-groups/actions', ['as' => 'menucat.actions', 'uses' => 'MenuCatController@multiActions']);
    //    Menu
    Route::delete('/menus/asyn-destroy', ['as' => 'menu.asyn_destroy', 'uses' => 'MenuController@asynDestroy']);
    Route::get('/menus/get-menu-type', ['as' => 'menu.get_type', 'uses' => 'MenuController@getType']);
    Route::get('/menus/get-list-type', ['as' => 'menu.get_list_type', 'uses' => 'MenuController@getListType']);
    Route::resource('menus', 'MenuController', rsNames('menu'));
    Route::post('/menus/actions', ['as' => 'menu.actions', 'uses' => 'MenuController@multiActions']);
    //    Post
    Route::resource('posts', 'PostController', rsNames('post'));
    Route::post('/posts/actions', ['as' => 'post.actions', 'uses' => 'PostController@multiActions']);
    //    Page
    Route::resource('pages', 'PageController', rsNames('page'));
    Route::post('/pages/actions', ['as' => 'page.actions', 'uses' => 'PageController@multiActions']);
    //    Files
    Route::get('/files/manage', ['as' => 'file.manage', 'uses' => 'FileController@manage']);
    Route::get('/files/dialog', ['as' => 'file.dialog', 'uses' => 'FileController@dialog']);
    Route::resource('files', 'FileController', rsNames('file'));
    Route::post('/files/actions', ['as' => 'file.actions', 'uses' => 'FileController@multiActions']);
    //    Medias
    Route::resource('medias', 'MediaController', rsNames('media'));
    Route::post('/medias/actions', ['as' => 'media.actions', 'uses' => 'MediaController@multiActions']);
    //    Slider & slide
    Route::resource('sliders', 'SliderController', rsNames('slider'));
    Route::post('/sliders/actions', ['as' => 'slider.actions', 'uses' => 'SliderController@multiActions']);
    Route::get('sliders/slides/index', ['as' => 'slide.index', 'uses' => 'SlideController@index']);
    Route::get('sliders/slides/create', ['as' => 'slide.create', 'uses' => 'SlideController@create']);
    Route::post('sliders/slides/store', ['as' => 'slide.store', 'uses' => 'SlideController@store']);
    Route::get('sliders/slides/{id}/edit', ['as' => 'slide.edit', 'uses' => 'SlideController@edit'])->where('id', '[0-9]+');
    Route::put('sliders/slides/{id}/update', ['as' => 'slide.update', 'uses' => 'SlideController@update'])->where('id', '[0-9]+');
    Route::delete('sliders/slides/{id}/delete', ['as' => 'slide.destroy', 'uses' => 'SlideController@destroy'])->where('id', '[0-9]+');
    Route::post('sliders/slides/actions', ['as' => 'slide.actions', 'uses' => 'SlideController@multiActions']);
    //    Options
    Route::resource('/options', 'OptionController', rsNames('option'));
    Route::post('/options/actions', ['as' => 'option.actions', 'uses' => 'OptionController@multiActions']);
    //    Comments
    Route::resource('/comments', 'CommentController', rsNames('comment'));
    Route::post('/comments/actions', ['as' => 'comment.actions', 'uses' => 'CommentController@multiActions']);
    //contact
    Route::resource('/contacts', 'ContactController', rsNames('contact'));
    Route::post('/contacts/actions', ['as' => 'contact.actions', 'uses' => 'ContactController@multiActions']);
    //visitor
    Route::get('/visitors', ['as' => 'visitor.index', 'uses' => 'VisitorController@index']);
    //Subscribes
    Route::resource('subscribes', 'SubscribeController', rsNames('subs'));
    Route::post('/subscribes/actions', ['as' => 'subs.actions', 'uses' => 'SubscribeController@multiActions']);
    
    //API
    Route::group([
        'prefix' => 'api',
        'as' => 'api.',
        'namespace' => 'Api'
    ], function () {
        Route::any('/ajax-action', ['as' => 'ajax_action', 'uses' => 'AjaxController@action']);

        Route::get('/posts', 'ApiController@getPosts')->name('get_posts');
        Route::get('/pages', 'ApiController@getPages')->name('get_pages');
        Route::get('/cats', 'ApiController@getCats')->name('get_cats');
        Route::get('/albums', 'ApiController@getAlbums')->name('get_albums');
        Route::get('/files', 'ApiController@getFiles')->name('get_files');
    });
});
