<?php

Route::group([
    'prefix' => 'dictionary',
    'as' => 'word.'
], function () {
    Route::get('/', 'DictController@index')->name('index');
    Route::get('/{word}_w{id}.html', 'DictController@viewWord')->name('view_word');
    Route::post('/make-word', 'DictController@makeWord')->name('make_word');

    Route::group([
        'middleware' => 'auth'
    ], function () {
        Route::delete('/words/{id}/delete', 'DictController@deleteWord')->name('delete');
        Route::get('/words/{id}/edit', 'DictController@editWord')->name('edit');
        Route::put('/words/{id}/update', 'DictController@updateWord')->name('update');
        Route::post('sentence/make', 'DictController@makeSentence')->name('make_sentence');
        Route::get('sentence/{id}/edit', 'DictController@editSentence')->where('id', '[0-9]+')->name('edit_sentence');
        Route::put('sentence/update', 'DictController@updateSentence')->where('id', '[0-9]+')->name('update_sentence');
        Route::delete('sentence/{id}/delete', 'DictController@deleteSentence')->where('id', '[0-9]+')->name('delete_sentence');
    });
});

$manage = config('admin.prefix');
Route::group([
    'prefix' => $manage . '/dicts',
    'middleware' => 'auth',
    'namespace' => 'Admin',
    'as' => 'admin.'
], function () {
    Route::get('/', 'DictController@index')->name('index');
    Route::get('/create', 'DictController@create')->name('create');
    Route::post('/store', 'DictController@store')->name('store');
    Route::get('/{id}/edit', 'DictController@edit')->name('edit');
    Route::put('/{id}/update', 'DictController@update')->name('update');
    Route::post('/actions', 'DictController@multiActions')->name('actions');
});

