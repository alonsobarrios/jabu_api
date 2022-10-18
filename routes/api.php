<?php

use Illuminate\Support\Facades\Route;

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

Route::namespace('v1')->prefix('v1')->group(function () {
    Route::get('/tickets/{id?}', 'TicketController@index');
    Route::post('/ticket', 'TicketController@store');
    Route::put('/ticket/{id}', 'TicketController@update');
    Route::delete('/ticket/{id}', 'TicketController@destroy');

    Route::get('/shoppers/{id?}', 'ShopperController@index');
    Route::post('/shopper', 'ShopperController@store');
    Route::put('/shopper/{id}', 'ShopperController@update');
    Route::delete('/shopper/{id}', 'ShopperController@destroy');

    Route::get('/bookings', 'BookingController@index');
    Route::post('/booking', 'BookingController@store');
    Route::put('/booking/{id}', 'BookingController@update');

    Route::get('/getTickets', 'TicketController@getTickets');
});
