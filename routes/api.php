<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('user')->group(function () {
    Route::post('login', 'API\LoginController@userlogin');
    Route::post('register', 'API\RegisterController@userregister');
    Route::get('logout', 'API\UserController@logout');
    Route::get('me', 'API\UserController@show');
    Route::post('update', 'API\UserController@update');
    Route::post('googleupdate', 'API\UserController@googleupdate');
    Route::post('gas_station', 'API\SearchController@getStation');
    Route::post('place_orders', 'API\OrderController@order');
    Route::post('show_order', 'API\OrderController@showorders');


});

Route::prefix('agency')->group(function () {
    Route::post('login', 'API\LoginController@agencylogin');
    Route::get('logout', 'API\AgencyController@logout');
    Route::post('register', 'API\RegisterController@agencyregister');
    Route::get('logout', 'API\AgencyController@logout')->middleware('auth:api');
    Route::get('me', 'API\AgencyController@show')->middleware('auth:api');
    Route::post('update', 'API\AgencyController@update');
    Route::post('googleupdate', 'API\AgencyController@googleupdate');
    Route::post('show_orders', 'API\AgencySearchController@showorders');

});
