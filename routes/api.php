<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('messages', 'MessagesController@index');
    Route::post('messages', 'MessagesController@store');
    Route::patch('messages/{id}', 'MessagesController@update');
    Route::delete('messages/{id}', 'MessagesController@destroy');

    Route::get('users', 'UsersController@index');
});

Route::post('users', 'UsersController@store');
Route::post('users/login', 'UsersController@login');
