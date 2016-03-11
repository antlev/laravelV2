<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

	Route::get('/home', function () {
	    return view('welcome');
	});
	Route::post('password/reset', 'Auth\PasswordController@postReset');
	Route::get('users', 'alexController@getInfos');
	Route::post('users', 'alexController@postInfos');
    //Route::get('/home', 'HomeController@index');
	Route::get('calendar','alexController@getCalendar');
	Route::get('calendars','alexController@postCalendar');
	Route::get('add_event','alexController@add_event');
	Route::get('lieu', 'alexController@autocomplete');
	Route::get('list_view/{table}', 'alexController@index');
	Route::get('update', 'alexController@update');



//profil
	Route::post('auth/{id}', 'Auth\AuthController@getUserName');
    Route::post('changeUserInfo','HomeController@changeUserInfo');
    Route::post('postimage','HomeController@postImage');
	Route::post('msg', 'HomeController@sbInsert');
	Route::post('shouts', 'HomeController@getShouts');
    Route::get('profil','HomeController@getProfil'); 
	Route::get('supp_view', 'alexController@supp_view');
	Route::get('create', 'alexController@postIndex');
	// Route::get('suivi_mot_passe', 'alexController@suivi_mot_passe');
	// Route::get('suivi_reserve', 'alexController@suivi_reserve');


	//Forum
	Route::get('/forum/', 'forumController@index');

	Route::get('/forum/{cat}', 'forumController@cat');
	Route::get('/forum/{cat}/newTopic', 'forumController@newTopic');

	Route::get('/forum/{cat}/{topic}', 'forumController@topic');
	Route::get('/forum/{cat}/{topic}/newMessage', 'forumController@newMessage');
	Route::post('/forum/newMessage','forumController@postMessage');

    Route::post('/forum/{cat}/{topic}/newMessage/savemsg','forumController@postMessage');

});
