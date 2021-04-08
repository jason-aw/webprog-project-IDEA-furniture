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

// /home -> bebas
// /login -> guest
// /register -> guest
// /logout -> logged in user
// crud product producttype -> admin
// show all product/producttype -> bebas
// Updetprof, cart, transact his, -> member


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Route::resource('product-type', 'ProductTypeController')->except(['index', 'show']);

Route::get('product-type/{product_type}/products', 'ProductController@index')->name('product-type.products.index');
Route::resource('products', 'ProductController')->except('index');

Route::get('/edit-profile', 'UserController@getEditProfilePage');
Route::put('/edit-profile', 'UserController@editProfile');

Route::get('/shopping-cart', 'CartController@index');
Route::post('/shopping-cart', 'CartController@store');
Route::post('/shopping-cart/delete-all', 'CartController@deleteAll');
Route::put('/shopping-cart/{product_id}', 'CartController@update');
Route::delete('/shopping-cart/{product_id}', 'CartController@destroy');

Route::post('/checkout', 'TransactionController@checkout');
Route::get('/transaction-history', 'TransactionController@index');