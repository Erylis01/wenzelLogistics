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
Route::post('auth/register', 'Auth\RegisterController@fillDolibarr')->name('fillDolibarr');

Route::get('/', 'HomeController@index')->name('home');

//PROFILE
Route::get('/profile', 'ProfileController@show')->name('showProfile');
Route::post('/profile', 'ProfileController@update')->name('updateProfile');
Route::delete('/profile', 'ProfileController@destroy')->name('destroyProfile');

//LOADINGS
Route::get('/loadings','ListLoadingsController@show')->name('showAllLoadings');
Route::get('/detailsLoading/{atrnr}', 'DetailsLoadingController@show')->name('showDetailsLoading');
Route::post('/detailsLoading/{atrnr}', 'DetailsLoadingController@save')->name('saveDetailsLoading');

//WAREHOUSES
Route::get('/allWarehouses', 'WarehousesController@showAll')->name('showAllWarehouses');
Route::get('/detailsWarehouses/{id}', 'WarehousesController@showDetails')->name('showDetailsWarehouse');
//Route::get('/addWarehouses', 'WarehousesController@add')->name('addWarehouse');

//PALLETS ACCOUNTS
//Route::get('/allWarehouses', 'WarehousesController@showTotal')->name('showAllWarehouses');


//MAILS
    //validate registration
Route::get('auth/registerVerify/{email_token}', 'Auth\RegisterController@verify')->name('validateRegistration');

