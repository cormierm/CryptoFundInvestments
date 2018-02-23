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

use App\Role;
use App\Risk;
Route::get('/init', function () {
    Role::create(['name'=>'Trader']);
    Role::create(['name'=>'Client']);
    Risk::create(['name'=>'Aggressive']);
    Risk::create(['name'=>'Balanced']);
    Risk::create(['name'=>'Conservative']);
    return "init done.";
});
