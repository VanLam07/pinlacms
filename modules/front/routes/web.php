<?php

Route::get('/', 'PageController@index')->name('home');

Route::group(['prefix' => 'account'], function () {
    Route::get('login-social/{driver}', 'AuthController@loginSocial')
            ->name('login_social');
    Route::get('login-social/callback', 'AuthController@handleLoginSocial')
            ->name('login_social.callback');
});

