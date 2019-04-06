<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', 'ConversationController@index')->name('conversations.index');

    Route::get('/conversations/{conversation}', 'ConversationController@show')->name('conversations.show');

    Route::delete('/conversations/{conversation}', 'ConversationController@leave')->name('conversations.leave');

    Route::post('/messages/{conversation}', 'MessageController@store')->name('messages.store');

    Route::post('search/user', 'SearchController@user')->name('search.user');

    Route::get('users/{user}', 'UserController@show')->name('users.show');
});


