<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/login', 'AuthController@login');
Route::post('/login', 'AuthController@dologin');

Route::get('/send', function() {
	return view('chat');
});
Route::post('/send', 'ChatController@send');

Route::group(['middleware' => ['web','authenticate']], function(){
	Route::get('/', 'MainController@index');
	Route::post('/', 'MainController@chatLobby');		
	Route::get('/make_room' , 'MainController@setRoom');
	Route::get('/room', 'MainController@getRoom');
});