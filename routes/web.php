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

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::middleware(['auth'])->group(function (){

    // auth App, laravel passport
    Route::get('/settings/developers',function (){
        return view('settings.auth');
    });

    // edit user avatar
    Route::get('/users/avatar/{user}/edit','UserAvatarController@edit');

    // upload user avatar
    Route::post('/users/avatar','UserAvatarController@update');

    Route::resource('users','UserController');

});
