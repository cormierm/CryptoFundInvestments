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
Route::get('/trader/{id}', 'UsersController@trader');
Route::get('/profile/edit', 'UsersController@edit');
Route::post('/profile/edit', 'UsersController@update');
Route::get('/profile/apply_trader_role', 'UsersController@apply_trader_role');
Route::get('/profile/remove_trader_role', 'UsersController@remove_trader_role');
Route::post('/profile/changePassword', 'UsersController@changePassword');

Route::resource('/funds', 'FundsController');

Route::get('/investments/create/{id}', 'InvestmentsController@create');
Route::post('/investments', 'InvestmentsController@store');
Route::post('/investments/approve', 'InvestmentsController@approve');

Route::post('/transactions', 'TransactionsController@store');



use App\Role;
use App\Risk;
Route::get('/test', function () {
    $fund = App\Fund::find(1);
    return $fund->marketValue();
//    $coin = App\Currency::all()->where('symbol', 'CAD')->first;
//    print($coin->latestCoinPrice->price_cad);
});
Route::get('/init', function () {
    Role::create(['name'=>'Trader']);
    Role::create(['name'=>'Client']);
    Risk::create(['name'=>'Aggressive']);
    Risk::create(['name'=>'Balanced']);
    Risk::create(['name'=>'Conservative']);
    \App\CurrencyType::create(['name'=>'Crypto']);
    \App\CurrencyType::create(['name'=>'Fiat']);
    \App\Currency::create(['name'=>'CAD','symbol'=>'CAD','currency_type_id'=>2,'coin_market_cap'=>null]);
    \App\CoinPrice::create(['currency_id'=>'1','price_cad'=>1]);
    \App\TransactionType::create(['name'=>'Buy']);
    \App\TransactionType::create(['name'=>'Sell']);
    \App\TransactionType::create(['name'=>'Investment']);
    return "init done.";
});
