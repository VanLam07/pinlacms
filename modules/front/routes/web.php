<?php

Route::get('/', 'PageController@index')->name('home');
Route::get('/blog', 'PageController@blog')->name('blog');

Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
    Route::get('login', 'AuthController@getLogin')
            ->name('login');
    Route::get('register', 'AuthController@getRegister')
            ->name('register');
    Route::post('login', 'AuthController@postLogin')
            ->name('post_login');
    Route::post('register', 'AuthController@postRegister')
            ->name('post_register');
    
    Route::get('login-social/{driver}', 'AuthController@loginSocial')
            ->name('login_social');
    Route::get('login-social/callback', 'AuthController@handleLoginSocial')
            ->name('login_social.callback');
    
    Route::get('/forget-password', 'AuthController@getForgetPass')
            ->name('get_forget_pass');
    Route::post('/forget-password', 'AuthController@postForgetPass')
            ->name('post_forget_pass');
    Route::get('/reset-password', 'AuthController@getResetPass')
            ->name('get_reset_pass');
    Route::post('/reset-password', 'AuthController@postResetPass')
            ->name('post_reset_pass');
});

Route::get('category/{id}/{slug?}', 'CatController@view')
        ->name('cat.view')->where('id', '[0-9]+');
Route::get('tag/{id}/{slug?}', 'TagController@view')
        ->name('tag.view')->where('id', '[0-9]+');
Route::get('post/{id}/{slug?}', 'PostController@view')
        ->name('post.view')->where('id', '[0-9]+');
//post notification
Route::post('post/{id}/save-notify', 'PostController@saveMailNotify')
        ->name('post.save_mail_notify')->where('id', '[0-9]+');
Route::get('page/{id}/{slug?}', 'PageController@view')
        ->name('page.view')->where('id', '[0-9]+');

Route::get('comment/lists', 'CommentController@loadData')
        ->name('comment.load');
//contact
Route::post('contact/send', 'PageController@sendContact')
        ->name('contact.send');
//album
Route::get('album/{id}/{slug?}', 'AlbumController@view')
        ->name('album.view');

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('account/logout', 'AuthController@logout')
            ->name('account.logout');
    Route::get('/account/profile', 'AuthController@getProfile')
            ->name('account.profile');
    Route::post('/account/profile/update', 'AuthController@updateProfile')
            ->name('account.update_profile');
    Route::get('/account/change-password', 'AuthController@getChangePass')
            ->name('account.change_pass');
    Route::post('/account/update-password', 'AuthController@updatePassword')
            ->name('account.update_pass');
    
    Route::group(['prefix' => 'comment', 'as' => 'comment.'], function () {
        Route::post('add', 'CommentController@store')
                ->name('add');
        Route::delete('{id}/delete', 'CommentController@delete')
                ->name('delete')->where('id', '[0-9]+');
        Route::get('{id}/edit', 'CommentController@edit')
                ->name('edit')->where('id', '[0-9]+');
        Route::put('update-item', 'CommentController@update')
                ->name('update');
    });
    
});