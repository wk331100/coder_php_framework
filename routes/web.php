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
use System\Route;

Route::get('/', 'IndexController@index');
Route::get('/home', 'HomeController@index');

Route::get('/user/list', 'UserController@index');
Route::get('/user/info', 'UserController@info');
Route::post('/user/create', 'UserController@create');
Route::post('/user/update', 'UserController@update');
Route::post('/user/delete', 'UserController@delete');


Route::prefix('account')->middleware(['auth','auth2'])->group(function (){
    Route::any('user', 'Account\UserController@index');
    Route::get('user/test', 'Account\UserController@test');
});








