<?php

Route::any('/ajax-action', ['as' => 'ajax_action', 'uses' => 'AjaxController@action']);

Route::get('/posts', 'ApiController@getPosts')->name('get_posts');
Route::get('/pages', 'ApiController@getPages')->name('get_pages');
Route::get('/cats', 'ApiController@getCats')->name('get_cats');
Route::get('/files', 'ApiController@getFiles')->name('get_files');