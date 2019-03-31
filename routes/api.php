<?php


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

Route::apiResource('/product-types', 'ProductTypeController');
Route::resource('/product', 'ProductController')->only([
    'index', 'show', 'store', 'destroy'
]);
Route::resource('/order', 'OrderController')->only([
    'index', 'show', 'store', 'destroy'
]);
Route::get('/product-types/{id}/orders', 'ProductTypeController@orders');