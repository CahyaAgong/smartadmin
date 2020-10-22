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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/agenda', 'HomeController@agenda')->name('agenda');
Route::get('/pengumuman', 'HomeController@pengumuman')->name('pengumuman');
Route::get('/runningtext', 'HomeController@runningtext')->name('runningtext');
Route::get('/account', 'HomeController@account')->name('account');

Route::group(['prefix' => 'account'], function () {
  Route::get('/getAccount', 'AccountController@getAccount')->name('account.get');
  Route::post('/addAccount', 'AccountController@addAccount')->name('account.add');
  Route::post('/deleteAccount', 'AccountController@deleteAccount')->name('account.delete');
  Route::post('/updateAccount', 'AccountController@updateAccount')->name('account.update');
});
