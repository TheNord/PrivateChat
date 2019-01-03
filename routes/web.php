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