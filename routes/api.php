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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::POST('/streams', 'StreamController@store');
Route::GET('/streams/{stream}', 'StreamController@show');
Route::GET('/streams', 'StreamController@index');
Route::PATCH('/streams/{stream}', 'StreamController@update');

Route::POST('/merchandises', 'MerchandiseController@store');
