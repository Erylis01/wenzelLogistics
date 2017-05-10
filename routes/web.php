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

//AUTH
Auth::routes();


Route::get('/', 'HomeController@index')->name('home');

//PROFILE
Route::get('/profile', 'ProfileController@show')->name('showProfile');
Route::post('/profile', 'ProfileController@update')->name('updateProfile');
Route::delete('/profile', 'ProfileController@destroy')->name('destroyProfile');

//Route::get('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('resetPasswordProfile');

//PALLETS
Route::get('/pallets','ListPalletController@show')->name('showPallet');

//MAILS
Route::get('/registerMail', 'MailController@register')->name('mailRegister');