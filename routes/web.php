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
Route::post('/detailsLoading/{atrnr}', 'DetailsLoadingController@update')->name('updateDetailsLoading');
Route::post('/detailsLoading/{atrnr}/{anz}', 'DetailsLoadingController@submitUpload')->name('submitUpload');
//Route::delete('/detailsLoading/{atrnr}/{name}', 'DetailsLoadingController@deleteDocument')->name('deleteDocument');

//WAREHOUSES
Route::get('/allWarehouses', 'WarehousesController@showAll')->name('showAllWarehouses');
Route::get('/detailsWarehouse/{id}', 'WarehousesController@showDetails')->name('showDetailsWarehouse');
Route::post('/detailsWarehouse/{id}', 'WarehousesController@update')->name('updateWarehouse');
Route::delete('/detailsWarehouse/{id}', 'WarehousesController@delete')->name('deleteWarehouse');
Route::post('/addWarehouse', 'WarehousesController@add')->name('addWarehouse');
Route::get('/addWarehouse', 'WarehousesController@showAdd')->name('showAddWarehouse');

//WAREHOUSES
Route::get('/allTrucks', 'TrucksController@showAll')->name('showAllTrucks');
Route::get('/detailsTruck/{id}', 'TrucksController@showDetails')->name('showDetailsTruck');
Route::post('/detailsTruck/{id}', 'TrucksController@update')->name('updateTruck');
Route::delete('/detailsTruck/{id}', 'TrucksController@delete')->name('deleteTruck');
Route::post('/addTruck', 'TrucksController@add')->name('addTruck');
Route::get('/addTruck', 'TrucksController@showAdd')->name('showAddTruck');

//PALLETS ACCOUNTS
Route::get('/allPalletsaccounts', 'PalletsaccountsController@showAll')->name('showAllPalletsaccounts');
Route::get('/detailsPalletsaccount/{id}', 'PalletsaccountsController@showDetails')->name('showDetailsPalletsaccount');
Route::post('/detailsPalletsaccount/{id}', 'PalletsaccountsController@update')->name('updatePalletsaccount');
Route::delete('/detailsPalletsaccount/{id}', 'PalletsaccountsController@delete')->name('deletePalletsaccount');
Route::post('/addPalletsaccount', 'PalletsaccountsController@add')->name('addPalletsaccount');
Route::get('/addPalletsaccount', 'PalletsaccountsController@showAdd')->name('showAddPalletsaccount');

//PALLETS TRANSFERS
Route::get('/allPalletstransfers', 'PalletstransfersController@showAll')->name('showAllPalletstransfers');
Route::post('/addPalletstransfer', 'PalletstransfersController@add')->name('addPalletstransfer');
Route::get('/addPalletstransfer', 'PalletstransfersController@showAdd')->name('showAddPalletstransfer');
Route::get('/detailsPalletstransfer/{id}', 'PalletstransfersController@showDetails')->name('showDetailsPalletstransfer');
Route::post('/detailsPalletstransfer/{id}', 'PalletstransfersController@update')->name('updatePalletstransfer');
Route::post('/detailsPalletstransfer/{id}/{palletsaccount_name}', 'PalletstransfersController@saveVerification')->name('saveVerificationPalletstransfer');
Route::delete('/detailsPalletstransfer/{id}', 'PalletstransfersController@delete')->name('deletePalletstransfer');

//MAILS
//validate registration
Route::get('auth/registerVerify/{email_token}', 'Auth\RegisterController@verify')->name('validateRegistration');

