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
Route::get('/detailsLoading/{id}', 'DetailsLoadingController@show')->name('showDetailsLoading');
//Route::post('/detailsLoading/{id}', 'DetailsLoadingController@save')->name('saveDetailsLoading');

//MAILS
    //validate registration
//Route::get('/login', 'MailController@validateRegistration')->name('validateRegistration');
//Route::get(‘register/verify/{token}’, ‘RegisterController@verify’)->name('validateRegistration');

Route::get('/test', function(){
    return view('test');
});