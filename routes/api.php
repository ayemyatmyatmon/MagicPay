<?php

use Illuminate\Http\Request;
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
Route::namespace('Api')->group(function(){
    Route::post('/register','AuthController@register');
    Route::post('/login','AuthController@login');

    Route::middleware('auth:api')->group(function(){
        Route::post('/logout','AuthController@logout');
        Route::get('profile','PageController@profile');
        
        Route::get('transaction','PageController@transaction');
        Route::get('transaction/{trx_id}','PageController@transactionDetail');

        Route::get('notification','PageController@notification');
        Route::get('notification/{id}','PageController@notificationDetail');
        Route::get('toAccountVerify','PageController@toAccountVerify');
        Route::get('transfer/confirmation','PageController@transferConfirmation');
        Route::post('transfer/complete','PageController@transferComplete');
        Route::get('scan-and-pay-transfer','PageController@scanAndPayTransfer');
        Route::get('scan-and-pay-confirmation','PageController@scanAndPayConfirmation');
        Route::post('scan-and-pay-complete','PageController@scanAndPayTransferComplete');
    });
});
