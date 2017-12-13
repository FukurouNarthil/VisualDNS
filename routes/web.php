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

Route::get('front-end/getdo.php', 'DNSController@store');

Route::get('/message','MessageController@index');
Route::post('/message','MessageController@store');

#Route::get('/inquire','DNSController@index');
#Route::post('/inquire','DNSController@store');
