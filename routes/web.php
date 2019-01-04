<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('getFriends', 'HomeController@getFriends');

Route::post('/session/create', 'SessionsController@create');

Route::post('/send/{session}', 'ChatController@send');
Route::post('/chats/{session}', 'ChatController@chats');
Route::post('/chats/{session}/read', 'ChatController@read');
Route::post('/chats/{session}/clear', 'ChatController@clear');

Route::post('/chats/{session}/block', 'ChatController@block');
Route::post('/chats/{session}/unblock', 'ChatController@unblock');

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'Admin',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home');

        Route::get('/chats', 'ChatsController@index')->name('chats.index');
        Route::get('/chats/{session}', 'ChatsController@show')->name('chats.show');
        Route::post('/chats/{session}/block', 'ChatsController@block')->name('chats.block');
        Route::post('/chats/{session}/unblock', 'ChatsController@unblock')->name('chats.unblock');
        Route::post('/chats/{session}/clear', 'ChatsController@clear')->name('chats.clear');
        Route::delete('/chats/{session}/destroy', 'ChatsController@destroy')->name('chats.destroy');
    });