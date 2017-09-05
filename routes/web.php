<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'mail'], function () {
    Route::get('forget-password', function () {
        return new App\Mails\ForgetPassword;
    });
});
