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




Route::prefix('admin')->name('admin.')->middleware('auth:admin_user')->namespace('backend')->group(function(){
    Route::get('/','AdminController@home')->name('home');

    // /admin-user         admin-user.index
    // /admin-user/create  admin-user.create
    // /admin-user    (post) admin-user.store
    // /admin-user/{id} admin-user.edit
    // /admin-user/{id} (put) admin-user.update

    // Admin user
    Route::resource('admin-user','AdminController');
    Route::get('/admin-user/datatable/ssd','AdminController@ssd');

    // User
    Route::resource('user','UserController');
    Route::get('/user/datatable/ssd','UserController@ssd');

    // Wallet
    Route::get('/wallet','WalletController@index')->name('wallet.index');
    Route::get('/wallet/datatable/ssd','WalletController@ssd');

    Route::get('/wallet/add-amount','WalletController@addAmount');
    Route::post('/wallet/add-amount/store','WalletController@addAmountStore');
    Route::get('/wallet/reduced-amount','WalletController@reducedAmount');
    Route::post('/wallet/reduced-amount/store','WalletController@reducedAmountStroe');


});
