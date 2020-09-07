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

Route::prefix('v1')->group(function () {
    Route::namespace('Api\v1')->group(function () {
        Route::prefix('/register')->group(function () {
            Route::get('/confirm/{token}', 'Auth\Registration\ConfirmController@handle')->name('api.registration.confirm');

            Route::post('/', 'Auth\Registration\RequestController@handle')->name('api.registration.request');
            Route::post('/send-new-token', 'Auth\Registration\GenerateNewTokenController@handle')->name('api.registration.send-new-token');
        });
    });
});
