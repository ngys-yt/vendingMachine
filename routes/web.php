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
// リクエスト方法(URL,コントローラー名＠メソッド名) ->名前
Route::get('/', 'VendingMachineController@index')->name('index');
Route::post('/insert', 'VendingMachineController@insert')->name('deposit');
Route::post('/refund', 'VendingMachineController@refund')->name('refund');

Route::post('/add', 'VendingMachineController@addMerchandise')->name('add_merchandise');

Route::post('/editView', 'VendingMachineController@editView')->name('editView');
Route::post('/editMerc', 'VendingMachineController@editMerc')->name('editMerc');

Route::post('/purchase', 'VendingMachineController@purchase')->name('purchase');
Route::post('/destroy', 'VendingMachineController@destroy')->name('destroy');


Route::view('/login', 'login')->middleware('guest');
Route::post('/edit', 'AuthController@login')->name('login');

Route::view('/register', 'register');
Route::post('/register', 'AuthController@register')->name('register');

Route::get('/logout', 'AuthController@logout')->name('logout');

Route::get('/test', 'VendingMachineController@test');