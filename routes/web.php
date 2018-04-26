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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@dashboard');

Route::get('/profile', 'UsersController@profile');
Route::get('/user/{id}', 'UsersController@userProfile');
Route::get('/trader/{id}', 'UsersController@trader');
Route::get('/profile/edit', 'UsersController@edit');
Route::post('/profile/edit', 'UsersController@update');
Route::post('/profile/requestTraderRole', 'UsersController@requestTraderRole');
Route::get('/profile/remove_trader_role', 'UsersController@remove_trader_role');
Route::post('/profile/changePassword', 'UsersController@changePassword');

Route::resource('/funds', 'FundsController');

Route::get('/investments', 'InvestmentsController@index');
Route::get('/investments/create/{id}', 'InvestmentsController@create');
Route::get('/investments/removal/{id}', 'InvestmentsController@removal');
Route::post('/investments/removal', 'InvestmentsController@removalRequest');
Route::post('/investments', 'InvestmentsController@store');
Route::post('/investments/approve', 'InvestmentsController@approve');
Route::post('/investments/refuse', 'InvestmentsController@refuse');

Route::post('/transactions', 'TransactionsController@store');

Route::get('/coinlookup', 'CoinLookupController@index');

Route::post('/investments/remove/cancel', 'FundsRemovalController@cancel');
Route::post('/investments/remove/approve', 'FundsRemovalController@approve');

Route::get('/admin', 'AdminController@index');
Route::post('/admin/approveTraderRequest', 'AdminController@approveTraderRequest');
Route::post('/admin/cancelTraderRequest', 'AdminController@cancelTraderRequest');


use App\Role;
use App\Risk;
Route::get('/test', function () {
    $fund = App\Fund::find(1);
    return $fund->userMarketValue();
//    $coin = App\Currency::all()->where('symbol', 'CAD')->first;
//    print($coin->latestCoinPrice->price_cad);
});