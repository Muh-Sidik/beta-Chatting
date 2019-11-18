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

//register
Route::post('/register', 'UserChatController@register');

//login
Route::post('/login', 'UserChatController@login');

//login phone
Route::post('/cek', 'UserChatController@get_where_phone');

//update profil
Route::post('/update', 'UserChatController@update_profil');

//check user
Route::get('/user/{id}', 'MessageChatController@index');

//get detailchat
Route::get('/chat/{id}', 'MessageChatController@getDetail');

//search
Route::get('/search/{no_detail_chat}', 'MessageChatController@search');

//kirim chat
Route::post('/messages', 'MessageChatController@sendchat');

//untuk QR
Route::post('login/QR/{phone}/{password}', 'UserChatController@qr');

//delete_chat
Route::delete('/delete', 'MessageChatController@delete_chat');
