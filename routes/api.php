<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//update password
Route::put('/update/password', 'UserChatController@update_password');

//update bio
Route::put('/update/bio', 'UserChatController@update_status');

// Update Photo Profile
Route::put('/update/photo', 'UserChatController@update_photo');


//search username
Route::post('/search/user', 'UserChatController@search');

//get phone
Route::post('/getphone', 'UserChatController@getWherePhone');

//register
Route::post('/register', 'UserChatController@register');

//login
Route::post('/login', 'UserChatController@login');

//get phone
Route::post('/cek', 'UserChatController@get_where_phone');

//edit profil
Route::put('/update', 'UserChatController@update');

// kirim pesan/chat
Route::post('/messages', 'MessageChatController@sendchat');
//edit chat
// Route::put('/messages/edit', 'MessageChatController@edit_chat');
//hapus chat
Route::delete('/messages/delete/{id}', 'MessageChatController@delete_chat');
//get detailchat
Route::get('/chat/{no_detail_chat}', 'MessageChatController@getDetail');

//untuk QR
Route::post('login/QR/{phone}/{password}', 'UserChatController@qr');

//hapus user
Route::delete('/delete/user/{id}', 'MessageChatController@delete_user');

//Check Chat
Route::post('/check_chat', 'MessageChatController@getChatHstory');