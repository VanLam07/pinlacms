<?php

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
    Route::get('/auth/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
    
});
