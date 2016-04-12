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

Route::group(['middleware' => ['web','authenticate']], function(){
	Route::get('/profile', 'MainController@profile');
	Route::get('/', 'MainController@index');
	Route::post('/', 'MainController@chatLobby');		
	Route::get('/game', 'MainController@getGame');
	Route::post('/join_room', 'MainController@joinRoom');
	Route::post('/make_room' , 'MainController@setRoom');
	Route::post('/turn', 'MainController@turn');

	Route::get('/test', 'MainController@test');		
	Route::get('/make_room' , 'MainController@setRoom');
	Route::get('/room', 'MainController@getRooms');	
	Route::get('/clear_room' , 'MainController@clearRoom');
	Route::get('/delete_room', 'MainController@deleteRoom');
	
});