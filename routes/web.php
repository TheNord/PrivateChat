<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('getFriends', 'HomeController@getFriends');
Route::post('/session/create', 'SessionsController@create');