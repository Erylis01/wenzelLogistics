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


Route::get('/home', 'HomeController@index')->name('home');

//PROFILE
Route::get('/profile', 'ProfileController@show')->name('showProfile');
Route::post('/profile', 'ProfileController@update')->name('updateProfile');
Route::delete('/profile', 'ProfileController@destroy')->name('destroyProfile');

//Route::get('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('resetPasswordProfile');

//LOADINGS
Route::get('/loadings','ListLoadingsController@show')->name('showAllLoadings');
//Route::get('/loadings/{token}', 'ListLoadingsController@refresh')->name('refreshLoadings');

//MAILS
    //validate registration
//Route::get('/login', 'MailController@validateRegistration')->name('validateRegistration');
//Route::get(‘register/verify/{token}’, ‘RegisterController@verify’)->name('validateRegistration');
