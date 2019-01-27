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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/event', 'EventController@TestEvent');
Route::get('/notification', 'NotificationController@index');
Route::get('/notification/create', 'NotificationController@create');
Route::get('/notification/read', 'NotificationController@read');
Route::get('/notification/delete', 'NotificationController@delete');
Route::get('/notification/send', 'NotificationController@send');
Route::get('/email/verify/{token}', 'Auth\RegisterController@activation')->name('email.verify');
Route::post('/resend-activation', 'Auth\RegisterController@ResendActivation')->name('resend-activation');
