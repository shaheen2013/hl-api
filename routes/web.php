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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/bouncer/{locale}/{brandId}', "BouncerController@redirectView")->where(['brandId' => '[0-9]+', "locale" => '[^0-9]{2,5}']);
// Route::post('/bouncer/{locale}/{brandId}', "BouncerController@redirectView")->where(['brandId' => '[0-9]+', "locale" => '[^0-9]{2,5}']);
// Route::get('/bouncer/checkin/{locale}/{brandId}', "BouncerController@checkinView")->where(['brandId' => '[0-9]+', "locale" => '[^0-9]{2,5}']);
Route::get('/{brandId}', "BouncerController@redirectView")->where(['brandId' => '[0-9]+']);
Route::post('/{brandId}', "BouncerController@redirectView")->where(['brandId' => '[0-9]+']);
Route::get('/{brandId}/checkin', "BouncerController@checkinView")->where(['brandId' => '[0-9]+']);
