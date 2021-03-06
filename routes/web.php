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
Route::post('/loadings','ListLoadingsController@uploadImport')->name('uploadImportLoadings');
Route::get('/detailsLoading/{atrnr}', 'DetailsLoadingController@show')->name('showDetailsLoading');
Route::post('/detailsLoading/{atrnr}', 'DetailsLoadingController@submitUpdateUpload')->name('submitUpdateUpload');
Route::get('/addSubloading/{atrnr}', 'DetailsLoadingController@showAdd')->name('showAddSubloading');
Route::post('/addSubloading/{atrnr}', 'DetailsLoadingController@add')->name('addSubloading');

//WAREHOUSES
Route::get('/allWarehouses', 'WarehousesController@showAll')->name('showAllWarehouses');
Route::post('/allWarehouses', 'WarehousesController@uploadImport')->name('uploadImportWarehouses');
Route::get('/detailsWarehouse/{id}', 'WarehousesController@showDetails')->name('showDetailsWarehouse');
Route::post('/detailsWarehouse/{id}', 'WarehousesController@update')->name('updateWarehouse');
Route::delete('/detailsWarehouse/{id}', 'WarehousesController@delete')->name('deleteWarehouse');
Route::post('/addWarehouse', 'WarehousesController@add')->name('addWarehouse');
Route::get('/addWarehouse/{originalPage}', 'WarehousesController@showAdd')->name('showAddWarehouse');

//TRUCKS
Route::get('/allTrucks/{refresh}/{nb}', 'TrucksController@showAll')->name('showAllTrucks');
Route::get('/detailsTruck/{id}', 'TrucksController@showDetails')->name('showDetailsTruck');
Route::post('/detailsTruck/{id}', 'TrucksController@update')->name('updateTruck');
Route::delete('/detailsTruck/{id}', 'TrucksController@delete')->name('deleteTruck');
Route::post('/addTruck', 'TrucksController@add')->name('addTruck');
Route::get('/addTruck/{originalPage}', 'TrucksController@showAdd')->name('showAddTruck');

//PALLETS ACCOUNTS
Route::get('/allPalletsaccounts/{nb}', 'PalletsaccountsController@showAll')->name('showAllPalletsaccounts');
Route::get('/detailsPalletsaccount/{id}', 'PalletsaccountsController@showDetails')->name('showDetailsPalletsaccount');
Route::post('/detailsPalletsaccount/{id}', 'PalletsaccountsController@update')->name('updatePalletsaccount');
Route::delete('/detailsPalletsaccount/{id}', 'PalletsaccountsController@delete')->name('deletePalletsaccount');
Route::post('/addPalletsaccount', 'PalletsaccountsController@add')->name('addPalletsaccount');
Route::get('/addPalletsaccount/{originalPage}', 'PalletsaccountsController@showAdd')->name('showAddPalletsaccount');

//PALLETS TRANSFERS
Route::get('/allPalletstransfers/{type}', 'PalletstransfersController@showAll')->name('showAllPalletstransfers');
Route::post('/addPalletstransfer', 'PalletstransfersController@add')->name('addPalletstransfer');
Route::get('/addPalletstransfer/{originalPage}', 'PalletstransfersController@showAdd')->name('showAddPalletstransfer');
//Route::get('/addPalletstransfer/{nameAccount}', 'PalletstransfersController@showAddAccount')->name('showAddPalletstransferAccount');
Route::get('/detailsPalletstransfer/{id}', 'PalletstransfersController@showDetails')->name('showDetailsPalletstransfer');
Route::post('/detailsPalletstransfer/{id}', 'PalletstransfersController@update')->name('updatePalletstransfer');
Route::delete('/detailsPalletstransfer/{id}', 'PalletstransfersController@delete')->name('deletePalletstransfer');

//TYPEAHED
Route::get('autocompleteAccount', 'PalletstransfersController@autocompleteAccount')->name('autocompleteAccount');

//MAILS
//validate registration
Route::get('auth/registerVerify/{email_token}', 'Auth\RegisterController@verify')->name('validateRegistration');

