<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware('languageswitcher')->group(function(){
    Auth::routes();
    // Route::middleware('auth')->get('/','frontend\PageController@home');

    Route::get('admin/login','Auth\AdminLoginController@showLoginForm')->name('admin.login-form');
    Route::post('admin/login','Auth\AdminLoginController@login')->name('admin.login');

    Route::get('/logout','Auth\AdminLoginController@logout')->name('admin.logout');



    Route::namespace('frontend')->middleware('auth')->group(function(){
        Route::get('/','PageController@home')->name('home');
        Route::get('/profile','PageController@profile')->name('profile');
        Route::get('/updatepassword','PageController@updatePassword')->name('updatepassword');
        Route::post('/updatepassword','PageController@updatePasswordStore')->name('updatepassword-store');
        Route::get('/wallet','PageController@wallet')->name('wallet');
        Route::get('/transfer','PageController@transfer')->name('transfer');

        Route::get('/transfer-hash','PageController@transferHash');

        Route::get('/transfer/confirmation','PageController@transferConfirmation');
        Route::post('/transfer/complete','PageController@transferComplete');

        Route::get('/transaction','PageController@transaction')->name('transaction');
        Route::get('/transaction/{trx_id}','PageController@transactionDetail');
        Route::get('/to-account-verify','PageController@toAccountVerify');
        Route::get('/check-password','PageController@checkPassword');
        Route::get('/received-qr','PageController@receivedQr');
        Route::get('/scan-and-pay','PageController@scanAndPay');
        Route::get('/scan-and-pay-transfer','PageController@scanAndPayTransfer');
        Route::get('/scan-and-pay-transferconfirm','PageController@scanAndPayTransferConfirm');
        Route::post('/scan-and-pay-transfercomplete','PageController@scanAndPayTransferComplete');
        Route::get('/notification','NotificationController@index');
        Route::get('/notification/{id}','NotificationController@show');

        Route::get('/language-switcher','PageController@languageSwitcher');
    });
});


