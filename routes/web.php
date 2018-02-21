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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'UsersController@profile');
Route::get('/profile/edit', 'UsersController@edit');
Route::post('/profile/edit', 'UsersController@update');
Route::get('/profile/apply_trader_role', 'UsersController@apply_trader_role');

use App\Role;
Route::get('/create_roles', function () {
    Role::create(['name'=>'Trader']);
    Role::create(['name'=>'Client']);
});
